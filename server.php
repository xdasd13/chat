<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/WebSocket/AveriasWebSocket.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\AveriasWebSocket;

// Crear el servidor WebSocket
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AveriasWebSocket()
        )
    ),
    8080 // Puerto del servidor WebSocket
);

echo "=== Servidor WebSocket de Averías ===\n";
echo "Servidor iniciado en puerto 8080\n";
echo "URL: ws://localhost:8080\n";
echo "Presiona Ctrl+C para detener el servidor\n";
echo "=====================================\n\n";

$server->run();
