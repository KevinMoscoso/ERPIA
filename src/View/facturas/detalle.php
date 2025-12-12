<?php
/** @var array $factura */
/** @var array $detalles */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Detalle de Factura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        crossorigin="anonymous"
    >
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Factura <?= htmlspecialchars($factura['numero'], ENT_QUOTES, 'UTF-8') ?></h1>

    <div class="mb-4">
        <strong>Fecha:</strong> <?= htmlspecialchars($factura['fecha'], ENT_QUOTES, 'UTF-8') ?><br>
        <strong>Cliente ID:</strong> <?= htmlspecialchars((string) $factura['cliente_id'], ENT_QUOTES, 'UTF-8') ?><br>
        <strong>Estado:</strong> <?= htmlspecialchars($factura['estado'], ENT_QUOTES, 'UTF-8') ?>
    </div>

    <h2 class="h5">Detalle</h2>

    <table class="table table-bordered table-striped mb-4">
        <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
            <th class="text-center">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($detalles)): ?>
            <?php foreach ($detalles as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['producto_nombre'] ?? $d['producto_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $d['cantidad'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format((float) $d['precio_unitario'], 2) ?></td>
                    <td><?= number_format((float) $d['subtotal'], 2) ?></td>
                    <td class="text-center">
                        <a href="/facturas/<?= $factura['id'] ?>/detalle/eliminar/<?= $d['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Eliminar línea?')">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No hay productos agregados.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <h2 class="h5">Agregar producto</h2>

    <form method="post" action="/facturas/<?= $factura['id'] ?>/detalle/guardar">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Producto ID</label>
                <input type="number" name="producto_id" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Cantidad</label>
                <input type="number" name="cantidad" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Precio Unitario</label>
                <input type="number" step="0.01" name="precio_unitario" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Agregar</button>
            </div>
        </div>
    </form>

    <a href="/facturas" class="btn btn-secondary mt-3">Volver a facturas</a>
</div>
</body>
</html>