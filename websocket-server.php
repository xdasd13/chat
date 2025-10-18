<?php

require_once 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\AveriasWebSocket;

// Configuración
$host = 'localhost';
$port = 8080;

echo "=================================\n";
echo "  Servidor WebSocket de Averías  \n";
echo "=================================\n";
echo "Iniciando servidor en {$host}:{$port}\n";
echo "Presiona Ctrl+C para detener\n";
echo "=================================\n\n";

try {
    // Crear el servidor WebSocket
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new AveriasWebSocket()
            )
        ),
        $port,
        $host
    );

    // Configurar el servidor para manejar señales de interrupción
    if (function_exists('pcntl_signal')) {
        pcntl_signal(SIGTERM, function() use ($server) {
            echo "\nRecibida señal de terminación. Cerrando servidor...\n";
            $server->loop->stop();
        });
        
        pcntl_signal(SIGINT, function() use ($server) {
            echo "\nRecibida señal de interrupción. Cerrando servidor...\n";
            $server->loop->stop();
        });
    }

    echo "Servidor WebSocket iniciado correctamente\n";
    echo "Los clientes pueden conectarse a: ws://{$host}:{$port}\n\n";
    
    // Iniciar el servidor
    $server->run();
    
} catch (Exception $e) {
    echo "Error al iniciar el servidor WebSocket: " . $e->getMessage() . "\n";
    echo "Verifica que el puerto {$port} no esté en uso.\n";
    exit(1);
}
