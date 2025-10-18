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
        try {
            // Sanitizar entrada
            $cliente = $this->request->getPost('cliente');
            $problema = $this->request->getPost('problema');

            // Validar datos
            if (!$this->validate([
                'cliente' => 'required|max_length[50]',
                'problema' => 'required|max_length[100]'
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Preparar datos para insertar
            $data = [
                'cliente' => $cliente,
                'problema' => $problema,
                'fechaHora' => date('Y-m-d H:i:s'),
                'status' => 'pendiente'
            ];

            // Iniciar transacción
            $db = \Config\Database::connect();
            $db->transStart();

            // Insertar en la base de datos
            $insertedId = $this->averiasModel->insert($data);
            if (!$insertedId) {
                throw new \Exception('Error al insertar en la base de datos');
            }

            // Obtener la avería recién insertada
            $nuevaAveria = $this->averiasModel->find($insertedId);
            if (!$nuevaAveria) {
                throw new \Exception('Error al recuperar la avería insertada');
            }

            // Enviar notificación WebSocket
            $webSocketClient = new WebSocketClient();
            if (!$webSocketClient->notifyNewAveria($nuevaAveria)) {
                log_message('warning', 'No se pudo enviar la notificación WebSocket');
            }

            // Completar transacción
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de base de datos');
            }

            return redirect()->to('/averias/listar')
                           ->with('success', 'Avería registrada exitosamente');

        } catch (\Exception $e) {
            log_message('error', '[Averias::guardar] Error: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al registrar la avería: ' . $e->getMessage());
        }
    }

    public function soluciones()
    {
        $data['averiasSolucionadas'] = $this->averiasModel
            ->where('status', 'solucionado')
            ->orderBy('fechaHora', 'DESC')
            ->findAll();
        return view('averias/soluciones', $data);
    }

    public function marcarSolucionada($id)
    {
        try {
            $averia = $this->averiasModel->find($id);
            
            if (!$averia) {
                throw new \Exception('Avería no encontrada');
            }

            $db = \Config\Database::connect();
            $db->transStart();

            // Actualizar estado
            $data = [
                'status' => 'solucionado',
                'fecha_solucion' => date('Y-m-d H:i:s')
            ];

            if (!$this->averiasModel->update($id, $data)) {
                throw new \Exception('Error al actualizar la avería');
            }

            // Obtener la avería actualizada
            $averiaActualizada = $this->averiasModel->find($id);
            
            // Enviar notificación WebSocket
            $webSocketClient = new WebSocketClient();
            $webSocketClient->notifyAveriaSolucionada($averiaActualizada);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Avería marcada como solucionada'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Averias::marcarSolucionada] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

}