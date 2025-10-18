<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Averías Solucionadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .table th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>Averías Solucionadas
                        </h4>
                        <div>
                            <button onclick="forceReconnect()" class="btn btn-light btn-sm me-2" title="Reconectar WebSocket">
                                <i class="fas fa-sync"></i> Reconectar
                            </button>
                            <a href="<?= base_url('averias/listar') ?>" class="btn btn-light btn-sm">
                                <i class="fas fa-list me-1"></i>Ver Pendientes
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Problema</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaSoluciones">
                                    <?php foreach ($averiasSolucionadas as $averia): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($averia['fechaHora'])) ?></td>
                                        <td><?= esc($averia['cliente']) ?></td>
                                        <td><?= esc($averia['problema']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($averia['fecha_solucion'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        // WebSocket mejorado
        let socket = null;
        let reconnectInterval = null;
        let reconnectAttempts = 0;
        let maxReconnectAttempts = 10;
        let isConnecting = false;
        let heartbeatInterval = null;

        function connectWebSocket() {
            if (isConnecting) return;
            
            isConnecting = true;
            
            try {
                if (socket && socket.readyState !== WebSocket.CLOSED) {
                    socket.close();
                }
                
                console.log('Conectando a WebSocket...');
                socket = new WebSocket('ws://localhost:8080');
                
                socket.onopen = function(event) {
                    console.log('Conectado al servidor WebSocket');
                    isConnecting = false;
                    reconnectAttempts = 0;
                    clearInterval(reconnectInterval);
                    showConnectionStatus(true);
                    startHeartbeat();
                };

                socket.onmessage = function(event) {
                    try {
                        const data = JSON.parse(event.data);
                        console.log('Mensaje recibido:', data);
                        
                        switch(data.type) {
                            case 'averia_solucionada':
                                addSolucionToTable(data.averia);
                                showNotification('Nueva avería solucionada: ' + data.averia.cliente, 'success');
                                break;
                            case 'pong':
                                console.log('Heartbeat recibido');
                                break;
                            default:
                                console.log('Tipo de mensaje desconocido:', data.type);
                        }
                    } catch (error) {
                        console.error('Error procesando mensaje:', error);
                    }
                };

                socket.onclose = function(event) {
                    console.log('Conexión WebSocket cerrada. Código:', event.code);
                    isConnecting = false;
                    showConnectionStatus(false);
                    stopHeartbeat();
                    
                    if (reconnectAttempts < maxReconnectAttempts) {
                        const delay = Math.min(1000 * Math.pow(2, reconnectAttempts), 30000);
                        console.log(`Reintentando en ${delay}ms (${reconnectAttempts + 1}/${maxReconnectAttempts})`);
                        
                        setTimeout(() => {
                            reconnectAttempts++;
                            connectWebSocket();
                        }, delay);
                    } else {
                        showNotification('Conexión perdida. Recarga la página.', 'error');
                    }
                };

                socket.onerror = function(error) {
                    console.error('Error WebSocket:', error);
                    isConnecting = false;
                    showConnectionStatus(false);
                };

            } catch (error) {
                console.error('Error creando WebSocket:', error);
                isConnecting = false;
                showConnectionStatus(false);
                
                if (reconnectAttempts < maxReconnectAttempts) {
                    setTimeout(() => {
                        reconnectAttempts++;
                        connectWebSocket();
                    }, 5000);
                }
            }
        }
        
        function startHeartbeat() {
            stopHeartbeat();
            heartbeatInterval = setInterval(() => {
                if (socket && socket.readyState === WebSocket.OPEN) {
                    socket.send(JSON.stringify({ type: 'ping' }));
                }
            }, 30000);
        }
        
        function stopHeartbeat() {
            if (heartbeatInterval) {
                clearInterval(heartbeatInterval);
                heartbeatInterval = null;
            }
        }
        
        function showConnectionStatus(connected) {
            let indicator = document.getElementById('ws-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'ws-indicator';
                indicator.style.cssText = `
                    position: fixed; top: 10px; right: 10px; padding: 5px 10px;
                    border-radius: 5px; font-size: 12px; z-index: 9999;
                `;
                document.body.appendChild(indicator);
            }

            if (connected) {
                indicator.innerHTML = '🟢 Tiempo Real Activo';
                indicator.style.backgroundColor = '#d4edda';
                indicator.style.color = '#155724';
            } else {
                indicator.innerHTML = '🔴 Reconectando...';
                indicator.style.backgroundColor = '#f8d7da';
                indicator.style.color = '#721c24';
            }
        }
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            let alertClass = 'alert-info';
            let iconClass = 'info-circle';
            
            switch(type) {
                case 'success':
                    alertClass = 'alert-success';
                    iconClass = 'check-circle';
                    break;
                case 'error':
                    alertClass = 'alert-danger';
                    iconClass = 'exclamation-circle';
                    break;
                case 'warning':
                    alertClass = 'alert-warning';
                    iconClass = 'exclamation-triangle';
                    break;
                default:
                    alertClass = 'alert-info';
                    iconClass = 'info-circle';
            }
            
            notification.className = `alert ${alertClass} alert-dismissible fade show`;
            notification.style.cssText = `
                position: fixed; top: 60px; right: 10px; z-index: 9998; min-width: 300px;
            `;
            notification.innerHTML = `
                <i class="fas fa-${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 5000);
        }
        
        function forceReconnect() {
            console.log('Forzando reconexión WebSocket...');
            reconnectAttempts = 0;
            if (socket) {
                socket.close();
            }
            showNotification('Reconectando WebSocket...', 'warning');
            connectWebSocket();
        }

        function addSolucionToTable(averia) {
            console.log('Agregando avería solucionada a la tabla:', averia);
            
            const tbody = document.getElementById('tablaSoluciones');
            if (!tbody) {
                console.error('No se encontró la tabla de soluciones');
                return;
            }
            
            // Verificar que la avería esté realmente solucionada
            if (averia.status !== 'solucionado') {
                console.log('La avería no está marcada como solucionada, ignorando');
                return;
            }
            
            // Verificar si la avería ya existe en la tabla
            const existingRows = tbody.querySelectorAll('tr');
            for (let existingRow of existingRows) {
                const cells = existingRow.querySelectorAll('td');
                if (cells.length >= 2) {
                    const clienteCell = cells[1].textContent.trim();
                    const problemaCell = cells[2].textContent.trim();
                    if (clienteCell === averia.cliente && problemaCell === averia.problema) {
                        console.log('La avería ya existe en la tabla, ignorando duplicado');
                        return;
                    }
                }
            }
            
            const row = document.createElement('tr');
            
            const fechaCreacion = new Date(averia.fechaHora).toLocaleString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Manejar fecha_solucion que puede ser null
            let fechaSolucion = 'Ahora';
            if (averia.fecha_solucion) {
                try {
                    fechaSolucion = new Date(averia.fecha_solucion).toLocaleString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (error) {
                    console.error('Error formateando fecha_solucion:', error);
                    fechaSolucion = 'Ahora';
                }
            }

            // Escapar HTML para prevenir XSS
            const clienteEscaped = escapeHtml(averia.cliente);
            const problemaEscaped = escapeHtml(averia.problema);

            row.innerHTML = `
                <td>${fechaCreacion}</td>
                <td>${clienteEscaped}</td>
                <td>${problemaEscaped}</td>
                <td>${fechaSolucion}</td>
            `;
            
            tbody.insertBefore(row, tbody.firstChild);
            row.classList.add('table-success');
            setTimeout(() => row.classList.remove('table-success'), 3000);
            
            console.log('Avería agregada exitosamente a la tabla');
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Conectar WebSocket cuando se carga la página
        document.addEventListener('DOMContentLoaded', connectWebSocket);
    </script>
</body>
</html>