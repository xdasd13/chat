<?php

namespace App\Libraries;

class WebSocketClient
{
    private $host;
    private $port;
    private $timeout;

    public function __construct($host = 'localhost', $port = 8080, $timeout = 5)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    /**
     * Enviar mensaje al servidor WebSocket
     */
    public function sendMessage($data)
    {
        try {
            // Crear conexión socket
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            
            if (!$socket) {
                throw new \Exception('No se pudo crear el socket');
            }

            // Configurar timeout
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $this->timeout, 'usec' => 0]);
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => $this->timeout, 'usec' => 0]);

            // Conectar al servidor WebSocket
            $result = socket_connect($socket, $this->host, $this->port);
            
            if (!$result) {
                throw new \Exception('No se pudo conectar al servidor WebSocket');
            }

            // Crear handshake WebSocket
            $key = base64_encode(random_bytes(16));
            $handshake = "GET / HTTP/1.1\r\n" .
                        "Host: {$this->host}:{$this->port}\r\n" .
                        "Upgrade: websocket\r\n" .
                        "Connection: Upgrade\r\n" .
                        "Sec-WebSocket-Key: {$key}\r\n" .
                        "Sec-WebSocket-Version: 13\r\n\r\n";

            socket_write($socket, $handshake);
            
            // Leer respuesta del handshake
            $response = socket_read($socket, 2048);
            
            if (strpos($response, '101 Switching Protocols') === false) {
                throw new \Exception('Handshake WebSocket falló');
            }

            // Enviar mensaje
            $message = json_encode($data);
            $frame = $this->createFrame($message);
            socket_write($socket, $frame);

            // Cerrar conexión
            socket_close($socket);
            
            return true;

        } catch (\Exception $e) {
            error_log("Error WebSocket: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear frame WebSocket
     */
    private function createFrame($message)
    {
        $length = strlen($message);
        $frame = chr(0x81); // FIN + opcode text

        if ($length < 126) {
            $frame .= chr($length | 0x80); // MASK + length
        } elseif ($length < 65536) {
            $frame .= chr(126 | 0x80) . pack('n', $length); // MASK + extended length
        } else {
            $frame .= chr(127 | 0x80) . pack('NN', 0, $length); // MASK + extended length
        }

        // Generar máscara
        $mask = random_bytes(4);
        $frame .= $mask;

        // Aplicar máscara al mensaje
        for ($i = 0; $i < $length; $i++) {
            $frame .= $message[$i] ^ $mask[$i % 4];
        }

        return $frame;
    }

    /**
     * Notificar nueva avería
     */
    public function notifyNewAveria($averia)
    {
        return $this->sendMessage([
            'type' => 'new_averia',
            'averia' => $averia
        ]);
    }

    /**
     * Notificar actualización de estado
     */
    public function notifyStatusUpdate($averia)
    {
        return $this->sendMessage([
            'type' => 'status_update',
            'averia' => $averia
        ]);
    }
}
