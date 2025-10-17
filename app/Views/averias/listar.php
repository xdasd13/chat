<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Averías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Averías
                        </h4>
                        <a href="<?= base_url('averias/registrar') ?>" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>Nueva Avería
                        </a>
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
                                                    <a href="<?= base_url('averias/actualizar/' . $averia['id']) ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Cambiar estado">
                                                        <?php if ($averia['status'] === 'pendiente'): ?>
                                                            <i class="fas fa-check me-1"></i>Marcar Solucionado
                                                        <?php else: ?>
                                                            <i class="fas fa-undo me-1"></i>Marcar Pendiente
                                                        <?php endif; ?>
                                                    </a>
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <script>
        // WebSocket para actualizaciones en tiempo real
        let socket = null;
        let reconnectInterval = null;

        function connectWebSocket() {
            try {
                socket = new WebSocket('ws://localhost:8080');
                
                socket.onopen = function(event) {
                    console.log('Conectado al servidor WebSocket');
                    clearInterval(reconnectInterval);
                    
                    // Mostrar indicador de conexión
                    showConnectionStatus(true);
                };

                socket.onmessage = function(event) {
                    const data = JSON.parse(event.data);
                    console.log('Mensaje recibido:', data);
                    
                    if (data.type === 'new_averia') {
                        addNewAveriaToTable(data.averia);
                        showNotification('Nueva avería registrada: ' + data.averia.cliente, 'success');
                    } else if (data.type === 'status_update') {
                        updateAveriaStatus(data.averia);
                        showNotification('Estado actualizado para: ' + data.averia.cliente, 'info');
                    }
                };

                socket.onclose = function(event) {
                    console.log('Conexión WebSocket cerrada');
                    showConnectionStatus(false);
                    
                    // Intentar reconectar cada 5 segundos
                    reconnectInterval = setInterval(connectWebSocket, 5000);
                };

                socket.onerror = function(error) {
                    console.error('Error WebSocket:', error);
                    showConnectionStatus(false);
                };

            } catch (error) {
                console.error('Error al conectar WebSocket:', error);
                showConnectionStatus(false);
                
                // Intentar reconectar cada 5 segundos
                reconnectInterval = setInterval(connectWebSocket, 5000);
            }
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
                    <a href="<?= base_url('averias/actualizar/') ?>${averia.id}" 
                       class="btn btn-sm btn-outline-primary" 
                       title="Cambiar estado">
                        <i class="fas fa-check me-1"></i>Marcar Solucionado
                    </a>
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
                            <a href="<?= base_url('averias/actualizar/') ?>${averia.id}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Cambiar estado">
                                <i class="fas fa-check me-1"></i>Marcar Solucionado
                            </a>
                        `;
                    } else {
                        statusCell.innerHTML = `
                            <span class="badge badge-solucionado">
                                <i class="fas fa-check me-1"></i>Solucionado
                            </span>
                        `;
                        actionCell.innerHTML = `
                            <a href="<?= base_url('averias/actualizar/') ?>${averia.id}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="Cambiar estado">
                                <i class="fas fa-undo me-1"></i>Marcar Pendiente
                            </a>
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
    </script>
</body>
</html>