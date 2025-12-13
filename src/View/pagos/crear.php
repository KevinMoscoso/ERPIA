<?php
/** @var array $factura */
/** @var array $errors */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registrar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Registrar pago - Factura <?= htmlspecialchars($factura['numero']) ?></h1>

    <form method="post" action="/pagos/guardar/<?= $factura['id'] ?>">
        <div class="mb-3">
            <label>Monto</label>
            <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Método de pago</label>
            <input type="text" name="metodo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" name="fecha" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="/pagos/<?= $factura['id'] ?>" class="btn btn-secondary">Volver</a>
            <button class="btn btn-primary">Guardar pago</button>
        </div>
    </form>
</div>
</body>
</html>