<?php
/** @var array $clientes */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Clientes - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        crossorigin="anonymous"
    >
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Listado de clientes</h1>
        <a href="/clientes/crear" class="btn btn-primary">Crear cliente</a>
    </div>

    <?php if (!empty($clientes)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $cliente['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $cliente['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($cliente['telefono'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($cliente['direccion'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center">
                            <a href="/clientes/editar/<?= htmlspecialchars((string) $cliente['id'], ENT_QUOTES, 'UTF-8') ?>"
                               class="btn btn-sm btn-warning me-1">
                                Editar
                            </a>
                            <a href="/clientes/eliminar/<?= htmlspecialchars((string) $cliente['id'], ENT_QUOTES, 'UTF-8') ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Seguro que deseas eliminar este cliente?');">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No hay clientes registrados.
        </div>
    <?php endif; ?>
</div>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
></script>
</body>
</html>