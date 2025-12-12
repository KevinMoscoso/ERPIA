<?php
$old = $old ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Crear factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Crear factura</h1>

    <form method="post" action="/facturas/guardar">
        <div class="mb-3">
            <label>Número</label>
            <input type="text" name="numero" class="form-control" required value="<?= htmlspecialchars($old['numero'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="<?= htmlspecialchars($old['fecha'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Cliente ID</label>
            <input type="number" name="cliente_id" class="form-control" required value="<?= htmlspecialchars($old['cliente_id'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Total</label>
            <input type="number" step="0.01" name="total" class="form-control" value="<?= htmlspecialchars($old['total'] ?? '0.00') ?>">
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-select">
                <?php foreach (['BORRADOR', 'EMITIDA', 'ANULADA'] as $e): ?>
                    <option value="<?= $e ?>"><?= $e ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="/facturas" class="btn btn-secondary">Volver</a>
            <button class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
</body>
</html>