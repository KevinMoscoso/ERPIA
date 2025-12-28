<?php
/** @var array $factura */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Editar factura</h1>

    <form method="post" action="/facturas/actualizar/<?= $factura['id'] ?>">

        <div class="mb-3">
            <label>Número</label>
            <input type="text" name="numero" class="form-control" required
                   value="<?= htmlspecialchars($factura['numero']) ?>">
        </div>

        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" name="fecha" class="form-control" required
                   value="<?= htmlspecialchars($factura['fecha']) ?>">
        </div>

        <div class="mb-3">
            <label>Cliente ID</label>
            <input type="number" name="cliente_id" class="form-control" required
                   value="<?= htmlspecialchars($factura['cliente_id']) ?>">
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-select">
                <?php foreach (['BORRADOR', 'EMITIDA', 'ANULADA'] as $e): ?>
                    <option value="<?= $e ?>" <?= $factura['estado'] === $e ? 'selected' : '' ?>>
                        <?= $e ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- TOTAL SOLO LECTURA -->
        <div class="mb-3">
            <label class="form-label">Total (calculado automáticamente)</label>
            <div class="form-control bg-light">
                <?= number_format((float) $factura['total'], 2) ?>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="/facturas" class="btn btn-secondary">Volver</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
</body>
</html>