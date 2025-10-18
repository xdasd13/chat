<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Averías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .badge-pendiente {
            background-color: #ffc107;
            color: #000;
        }
        .badge-solucionado {
            background-color: #28a745;
            color: #fff;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
        .fade-out {
            transition: all 0.5s ease-out;
            opacity: 0;
            transform: translateX(-100%);
        }
        .swal2-popup {
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>Averías Pendientes
                        </h4>
                        <div>
                            <a href="<?= base_url('averias/soluciones') ?>" class="btn btn-light me-2">
                                <i class="fas fa-check-circle me-1"></i>Ver Solucionadas
                            </a>
                            <a href="<?= base_url('averias/registrar') ?>" class="btn btn-light">
                                <i class="fas fa-plus me-1"></i>Nueva Avería
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Flujo de trabajo:</strong> Las averías aparecen aquí como "Pendientes". Al marcarlas como "Solucionadas", se mueven automáticamente a la vista de soluciones y desaparecen de esta lista.
                        </div>

                        <?php if (empty($averias)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay averías registradas</h5>
                                <p class="text-muted">Comienza registrando tu primera avería</p>
                                <a href="<?= base_url('averias/registrar') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Registrar Primera Avería
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">
                                                <i class="fas fa-user me-1"></i>Cliente
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Problema
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-calendar me-1"></i>Fecha y Hora
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-flag me-1"></i>Estado
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-cogs me-1"></i>Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($averias as $index => $averia): ?>
                                            <tr>
                                                <th scope="row"><?= $averia['id'] ?></th>
                                                <td>
                                                    <strong><?= esc($averia['cliente']) ?></strong>
                                                </td>
                                                <td>
                                                    <?= esc($averia['problema']) ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y H:i', strtotime($averia['fechaHora'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php if ($averia['status'] === 'pendiente'): ?>
                                                        <span class="badge badge-pendiente">
                                                            <i class="fas fa-clock me-1"></i>Pendiente
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-solucionado">
                                                            <i class="fas fa-check me-1"></i>Solucionado
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($averia['status'] === 'pendiente'): ?>
                                                        <button onclick="confirmarSolucion(<?= $averia['id'] ?>, '<?= esc($averia['cliente']) ?>')" 
                                                               class="btn btn-sm btn-outline-success" 
                                                               title="Marcar como solucionado">
                                                            <i class="fas fa-check me-1"></i>Marcar Solucionado
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="badge badge-solucionado">
                                                            <i class="fas fa-check-circle me-1"></i>Completado
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            Total de averías: <strong><?= count($averias) ?></strong>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small class="text-muted">
                                            Pendientes: <strong><?= count(array_filter($averias, fn($a) => $a['status'] === 'pendiente')) ?></strong> |
                                            Solucionadas: <strong><?= count(array_filter($averias, fn($a) => $a['status'] === 'solucionado')) ?></strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <script>
        // WebSocket para actualizaciones en tiempo real
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
                // Cerrar conexión anterior si existe
                if (socket && socket.readyState !== WebSocket.CLOSED) {
                    socket.close();
                }
                
                console.log('Intentando conectar WebSocket...');
                socket = new WebSocket('ws://localhost:8080');
                
                socket.onopen = function(event) {
                    console.log('Conectado al servidor WebSocket');
                    isConnecting = false;
                    reconnectAttempts = 0;
                    clearInterval(reconnectInterval);
                    
                    // Mostrar indicador de conexión
                    showConnectionStatus(true);
                    
                    // Iniciar heartbeat para mantener la conexión viva
                    startHeartbeat();
                };

                socket.onmessage = function(event) {
                    try {
                        const data = JSON.parse(event.data);
                        console.log('Mensaje recibido:', data);
                        
                        // Manejar diferentes tipos de mensajes
                        switch(data.type) {
                            case 'new_averia':
                                addNewAveriaToTable(data.averia);
                                showNotification('Nueva avería registrada: ' + data.averia.cliente, 'success');
                                break;
                            case 'status_update':
                                updateAveriaStatus(data.averia);
                                showNotification('Estado actualizado para: ' + data.averia.cliente, 'info');
                                break;
                            case 'averia_solucionada':
                                updateAveriaStatus(data.averia);
                                showNotification('Avería solucionada: ' + data.averia.cliente, 'success');
                                break;
                            case 'pong':
                                // Respuesta del heartbeat
                                console.log('Heartbeat recibido');
                                break;
                            default:
                                console.log('Tipo de mensaje desconocido:', data.type);
                        }
                    } catch (error) {
                        console.error('Error procesando mensaje WebSocket:', error);
                    }
                };

                socket.onclose = function(event) {
                    console.log('Conexión WebSocket cerrada. Código:', event.code, 'Razón:', event.reason);
                    isConnecting = false;
                    showConnectionStatus(false);
                    stopHeartbeat();
                    
                    // Reconectar automáticamente si no se ha alcanzado el límite
                    if (reconnectAttempts < maxReconnectAttempts) {
                        const delay = Math.min(1000 * Math.pow(2, reconnectAttempts), 30000); // Backoff exponencial
                        console.log(`Reintentando conexión en ${delay}ms (intento ${reconnectAttempts + 1}/${maxReconnectAttempts})`);
                        
                        setTimeout(() => {
                            reconnectAttempts++;
                            connectWebSocket();
                        }, delay);
                    } else {
                        console.log('Máximo número de intentos de reconexión alcanzado');
                        showNotification('Conexión WebSocket perdida. Recarga la página para reconectar.', 'error');
                    }
                };

                socket.onerror = function(error) {
                    console.error('Error WebSocket:', error);
                    isConnecting = false;
                    showConnectionStatus(false);
                };

            } catch (error) {
                console.error('Error al crear WebSocket:', error);
                isConnecting = false;
                showConnectionStatus(false);
                
                // Reintentar después de un delay
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
            }, 30000); // Enviar ping cada 30 segundos
        }
        
        function stopHeartbeat() {
            if (heartbeatInterval) {
                clearInterval(heartbeatInterval);
                heartbeatInterval = null;
            }
        }
        
        // Función para reconectar manualmente
        function forceReconnect() {
            reconnectAttempts = 0;
            if (socket) {
                socket.close();
            }
            connectWebSocket();
        }

        function addNewAveriaToTable(averia) {
            const tableBody = document.querySelector('tbody');
            if (!tableBody) return;

            // Crear nueva fila
            const newRow = document.createElement('tr');
            newRow.className = 'table-success'; // Resaltar nueva fila
            
            const fechaFormateada = new Date(averia.fechaHora).toLocaleString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            newRow.innerHTML = `
                <th scope="row">${averia.id}</th>
                <td><strong>${escapeHtml(averia.cliente)}</strong></td>
                <td>${escapeHtml(averia.problema)}</td>
                <td><small class="text-muted">${fechaFormateada}</small></td>
                <td>
                    <span class="badge badge-pendiente">
                        <i class="fas fa-clock me-1"></i>Pendiente
                    </span>
                </td>
                <td>
                    <button onclick="confirmarSolucion(${averia.id}, '${escapeHtml(averia.cliente)}')" 
                           class="btn btn-sm btn-outline-success" 
                           title="Marcar como solucionado">
                        <i class="fas fa-check me-1"></i>Marcar Solucionado
                    </button>
                </td>
            `;

            // Insertar al inicio de la tabla
            tableBody.insertBefore(newRow, tableBody.firstChild);

            // Quitar resaltado después de 3 segundos
            setTimeout(() => {
                newRow.classList.remove('table-success');
            }, 3000);

            // Actualizar contadores
            updateCounters();
        }

        function updateAveriaStatus(averia) {
            // Buscar la fila por ID
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const idCell = row.querySelector('th');
                if (idCell && idCell.textContent == averia.id) {
                    // Actualizar badge de estado
                    const statusCell = row.cells[4];
                    const actionCell = row.cells[5];
                    
                    if (averia.status === 'pendiente') {
                        statusCell.innerHTML = `
                            <span class="badge badge-pendiente">
                                <i class="fas fa-clock me-1"></i>Pendiente
                            </span>
                        `;
                        actionCell.innerHTML = `
                            <button onclick="confirmarSolucion(${averia.id}, '${escapeHtml(averia.cliente)}')" 
                                   class="btn btn-sm btn-outline-success" 
                                   title="Marcar como solucionado">
                                <i class="fas fa-check me-1"></i>Marcar Solucionado
                            </button>
                        `;
                    } else {
                        statusCell.innerHTML = `
                            <span class="badge badge-solucionado">
                                <i class="fas fa-check me-1"></i>Solucionado
                            </span>
                        `;
                        actionCell.innerHTML = `
                            <span class="badge badge-solucionado">
                                <i class="fas fa-check-circle me-1"></i>Completado
                            </span>
                        `;
                    }
                    
                    // Resaltar fila actualizada
                    row.classList.add('table-info');
                    setTimeout(() => {
                        row.classList.remove('table-info');
                    }, 2000);
                    
                    // Actualizar contadores
                    updateCounters();
                    return;
                }
            });
        }

        function updateCounters() {
            const rows = document.querySelectorAll('tbody tr');
            const total = rows.length;
            let pendientes = 0;
            let solucionadas = 0;

            rows.forEach(row => {
                const statusBadge = row.querySelector('.badge');
                if (statusBadge && statusBadge.textContent.includes('Pendiente')) {
                    pendientes++;
                } else if (statusBadge && statusBadge.textContent.includes('Solucionado')) {
                    solucionadas++;
                }
            });

            // Actualizar contadores en la interfaz
            const totalElement = document.querySelector('.col-md-6 strong');
            const countersElement = document.querySelector('.col-md-6.text-end');
            
            if (totalElement) {
                totalElement.textContent = total;
            }
            
            if (countersElement) {
                countersElement.innerHTML = `
                    <small class="text-muted">
                        Pendientes: <strong>${pendientes}</strong> |
                        Solucionadas: <strong>${solucionadas}</strong>
                    </small>
                `;
            }
        }

        function showConnectionStatus(connected) {
            // Crear o actualizar indicador de conexión
            let indicator = document.getElementById('ws-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'ws-indicator';
                indicator.style.cssText = `
                    position: fixed;
                    top: 10px;
                    right: 10px;
                    padding: 5px 10px;
                    border-radius: 5px;
                    font-size: 12px;
                    z-index: 9999;
                `;
                document.body.appendChild(indicator);
            }

            if (connected) {
                indicator.innerHTML = '🟢 Tiempo Real Activo';
                indicator.style.backgroundColor = '#d4edda';
                indicator.style.color = '#155724';
                indicator.style.border = '1px solid #c3e6cb';
            } else {
                indicator.innerHTML = '🔴 Reconectando...';
                indicator.style.backgroundColor = '#f8d7da';
                indicator.style.color = '#721c24';
                indicator.style.border = '1px solid #f5c6cb';
            }
        }

        function showNotification(message, type) {
            // Crear notificación temporal
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show`;
            notification.style.cssText = `
                position: fixed;
                top: 60px;
                right: 10px;
                z-index: 9998;
                min-width: 300px;
            `;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Conectar al WebSocket cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            connectWebSocket();
        });

        // Cerrar conexión cuando se cierra la página
        window.addEventListener('beforeunload', function() {
            if (socket) {
                socket.close();
            }
        });

        // Función para confirmar solución con SweetAlert
        function confirmarSolucion(averiaId, clienteNombre) {
            Swal.fire({
                title: '¿El problema fue solucionado?',
                text: `¿Confirmas que el problema de ${clienteNombre} ha sido solucionado?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, solucionado',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    marcarComoSolucionado(averiaId);
                }
            });
        }

        // Función para marcar como solucionado via AJAX
        function marcarComoSolucionado(averiaId) {
            // Mostrar loading
            Swal.fire({
                title: 'Procesando...',
                text: 'Marcando avería como solucionada',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`<?= base_url('averias/marcarSolucionada/') ?>${averiaId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover la fila de la tabla
                    removeAveriaFromTable(averiaId);
                    
                    // Mostrar éxito
                    Swal.fire({
                        title: '¡Solucionado!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Actualizar contadores
                    updateCounters();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error al procesar la solicitud',
                    icon: 'error'
                });
            });
        }


        // Función para remover avería de la tabla
        function removeAveriaFromTable(averiaId) {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const idCell = row.querySelector('th');
                if (idCell && idCell.textContent == averiaId) {
                    // Animación de salida
                    row.style.transition = 'all 0.5s ease-out';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-100%)';
                    
                    setTimeout(() => {
                        row.remove();
                        updateCounters();
                    }, 500);
                }
            });
        }

        // Modificar updateAveriaStatus para manejar averías solucionadas
        function updateAveriaStatusOriginal(averia) {
            // Si la avería se marcó como solucionada, removerla de la tabla
            if (averia.status === 'solucionado') {
                removeAveriaFromTable(averia.id);
                return;
            }
            
            // Para averías que se marcan como pendientes (solo debería pasar con nuevas averías)
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const idCell = row.querySelector('th');
                if (idCell && idCell.textContent == averia.id) {
                    const statusCell = row.cells[4];
                    const actionCell = row.cells[5];
                    
                    statusCell.innerHTML = `
                        <span class="badge badge-pendiente">
                            <i class="fas fa-clock me-1"></i>Pendiente
                        </span>
                    `;
                    actionCell.innerHTML = `
                        <button onclick="confirmarSolucion(${averia.id}, '${escapeHtml(averia.cliente)}')" 
                               class="btn btn-sm btn-outline-success" 
                               title="Marcar como solucionado">
                            <i class="fas fa-check me-1"></i>Marcar Solucionado
                        </button>
                    `;
                    
                    // Resaltar fila actualizada
                    row.classList.add('table-info');
                    setTimeout(() => {
                        row.classList.remove('table-info');
                    }, 2000);
                    
                    updateCounters();
                }
            });
        }

        // Reemplazar la función updateAveriaStatus original
        updateAveriaStatus = updateAveriaStatusOriginal;
    </script>
</body>
</html>