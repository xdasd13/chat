<?php

namespace App\Controllers;

use App\Models\AveriasModel;
use App\Libraries\WebSocketClient;

class Averias extends BaseController
{
    protected $averiasModel;

    public function __construct()
    {
        $this->averiasModel = new AveriasModel();
    }

    public function index()
    {
        return $this->listar();
    }

    public function listar()
    {
        $data['averias'] = $this->averiasModel->orderBy('fechaHora', 'DESC')->findAll();
        return view('averias/listar', $data);
    }

    public function registrar()
    {
        return view('averias/registrar');
    }

    public function guardar()
    {
        // Validar datos
        if (!$this->validate([
            'cliente' => 'required|max_length[50]',
            'problema' => 'required|max_length[100]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Preparar datos para insertar
        $data = [
            'cliente' => $this->request->getPost('cliente'),
            'problema' => $this->request->getPost('problema'),
            'fechaHora' => date('Y-m-d H:i:s'), // Fecha y hora actual del sistema
            'status' => 'pendiente' // Estado automático por defecto
        ];

        // Insertar en la base de datos
        if ($insertedId = $this->averiasModel->insert($data)) {
            // Obtener la avería recién insertada
            $nuevaAveria = $this->averiasModel->find($insertedId);
            
            // Enviar notificación WebSocket
            $webSocketClient = new WebSocketClient();
            $webSocketClient->notifyNewAveria($nuevaAveria);
            
            return redirect()->to('/averias/listar')->with('success', 'Avería registrada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al registrar la avería');
        }
    }

    public function actualizar($id)
    {
        $averia = $this->averiasModel->find($id);
        
        if (!$averia) {
            return redirect()->to('/averias/listar')->with('error', 'Avería no encontrada');
        }

        // Cambiar status
        $nuevoStatus = $averia['status'] === 'pendiente' ? 'solucionado' : 'pendiente';
        
        if ($this->averiasModel->update($id, ['status' => $nuevoStatus])) {
            // Obtener la avería actualizada
            $averiaActualizada = $this->averiasModel->find($id);
            
            // Enviar notificación WebSocket
            $webSocketClient = new WebSocketClient();
            $webSocketClient->notifyStatusUpdate($averiaActualizada);
            
            return redirect()->to('/averias/listar')->with('success', 'Status actualizado exitosamente');
        } else {
            return redirect()->to('/averias/listar')->with('error', 'Error al actualizar el status');
        }
    }
}