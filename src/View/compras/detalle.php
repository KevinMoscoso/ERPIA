<?php
/** @var array $compra */
/** @var array $detalles */
$err = isset($_GET['error']) ? (string) $_GET['error'] : '';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Detalle de compra - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Compra <?= htmlspecialchars((string) ($compra['numero'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>
        <a href="/compras" class="btn btn-secondary">Volver</a>
    </div>

    <?php if ($err !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="mb-4">
        <strong>Fecha:</strong> <?= htmlspecialchars((string) ($compra['fecha'] ?? ''), ENT_QUOTES, 'UTF-8') ?><br>
        <strong>Proveedor:</strong> <?= htmlspecialchars((string) ($compra['proveedor_nombre'] ?? ($compra['proveedor_id'] ?? '')), ENT_QUOTES, 'UTF-8') ?><br>
        <strong>Total:</strong> <?= number_format((float) ($compra['total'] ?? 0), 2) ?>
    </div>

    <h2 class="h5">Agregar producto</h2>
    <form method="post" action="/compras/guardarDetalle/<?= (int) $compra['id'] ?>">
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Producto ID</label>
                <input type="number" name="producto_id" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad</label>
                <input type="number" name="cantidad" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio Unitario</label>
                <input type="number" step="0.01" name="precio_unitario" class="form-control" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit">Agregar</button>
            </div>
        </div>
    </form>

    <hr class="my-4">

    <h2 class="h5">Detalle</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($detalles)): ?>
                <?php foreach ($detalles as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($d['producto_nombre'] ?? $d['producto_id']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= (int) $d['cantidad'] ?></td>
                        <td><?= number_format((float) $d['precio_unitario'], 2) ?></td>
                        <td><?= number_format((float) $d['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-4">No hay productos agregados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>