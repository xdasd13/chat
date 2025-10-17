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
</body>
</html>