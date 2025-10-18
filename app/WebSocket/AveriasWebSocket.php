<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class AveriasWebSocket implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "Servidor WebSocket de Averías iniciado\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Almacenar la nueva conexión
        $this->clients->attach($conn);
        echo "Nueva conexión: ({$conn->resourceId})\n";
        echo "Total conexiones: " . count($this->clients) . "\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        
        if ($data && isset($data['type'])) {
            switch ($data['type']) {
                case 'new_averia':
                    // Enviar la nueva avería a todos los clientes conectados
                    $this->broadcastNewAveria($data['averia']);
                    break;
                
                case 'status_update':
                    // Enviar actualización de estado a todos los clientes
                    $this->broadcastStatusUpdate($data['averia']);
                    break;
                
                case 'averia_solucionada':
                    // Enviar avería solucionada a todos los clientes
                    $this->broadcastAveriaSolucionada($data['averia']);
                    break;
                
                case 'ping':
                    // Responder al heartbeat
                    $from->send(json_encode(['type' => 'pong']));
                    echo "Heartbeat respondido a conexión {$from->resourceId}\n";
                    break;
                
                default:
                    echo "Tipo de mensaje desconocido: " . $data['type'] . "\n";
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Remover la conexión cerrada
        $this->clients->detach($conn);
        echo "Conexión {$conn->resourceId} desconectada\n";
        echo "Total conexiones: " . count($this->clients) . "\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Enviar nueva avería a todos los clientes conectados
     */
    private function broadcastNewAveria($averia)
    {
        $message = json_encode([
            'type' => 'new_averia',
            'averia' => $averia
        ]);

        foreach ($this->clients as $client) {
            $client->send($message);
        }

        echo "Nueva avería enviada a " . count($this->clients) . " clientes\n";
    }

    /**
     * Enviar actualización de estado a todos los clientes conectados
     */
    private function broadcastStatusUpdate($averia)
    {
        $message = json_encode([
            'type' => 'status_update',
            'averia' => $averia
        ]);

        foreach ($this->clients as $client) {
            $client->send($message);
        }

        echo "Actualización de estado enviada a " . count($this->clients) . " clientes\n";
    }

    /**
     * Enviar avería solucionada a todos los clientes conectados
     */
    private function broadcastAveriaSolucionada($averia)
    {
        $message = json_encode([
            'type' => 'averia_solucionada',
            'averia' => $averia
        ]);

        foreach ($this->clients as $client) {
            $client->send($message);
        }

        echo "Avería solucionada enviada a " . count($this->clients) . " clientes\n";
    }
}
