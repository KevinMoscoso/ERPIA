<?php
/** @var array $factura */
/** @var array $pagos */
/** @var float $totalPagado */
/** @var float $saldo */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Pagos de Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Pagos - Factura <?= htmlspecialchars($factura['numero']) ?></h1>

    <a href="/pagos/crear/<?= $factura['id'] ?>" class="btn btn-primary mb-3">Registrar pago</a>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Método</th>
            <th>Monto</th>
            <th class="text-center">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pagos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['fecha']) ?></td>
                <td><?= htmlspecialchars($p['metodo']) ?></td>
                <td><?= number_format((float) $p['monto'], 2) ?></td>
                <td class="text-center">
                    <a href="/pagos/eliminar/<?= $p['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar pago?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <strong>Total pagado:</strong> <?= number_format($totalPagado, 2) ?><br>
        <strong>Saldo pendiente:</strong> <?= number_format($saldo, 2) ?>
    </div>

    <a href="/facturas" class="btn btn-secondary mt-3">Volver a facturas</a>
</div>
</body>
</html>