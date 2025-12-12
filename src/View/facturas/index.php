<?php
/** @var array $facturas */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Facturas - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between mb-3">
        <h1 class="h4">Facturas</h1>
        <a href="/facturas/crear" class="btn btn-primary">Crear factura</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Número</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($facturas as $f): ?>
            <tr>
                <td><?= htmlspecialchars($f['numero']) ?></td>
                <td><?= htmlspecialchars($f['fecha']) ?></td>
                <td><?= htmlspecialchars($f['cliente_nombre'] ?? $f['cliente_id']) ?></td>
                <td><?= number_format((float) $f['total'], 2) ?></td>
                <td><?= htmlspecialchars($f['estado']) ?></td>
                <td class="text-center">
                    <a href="/facturas/editar/<?= $f['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="/facturas/eliminar/<?= $f['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar factura?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>