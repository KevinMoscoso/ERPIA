<?php
$old = $old ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Crear compra - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Crear compra</h1>

    <form method="post" action="/compras/guardar">
        <div class="mb-3">
            <label class="form-label">Número</label>
            <input type="text" name="numero" class="form-control" required value="<?= htmlspecialchars((string) ($old['numero'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="<?= htmlspecialchars((string) ($old['fecha'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Proveedor ID (opcional)</label>
            <input type="number" name="proveedor_id" class="form-control" value="<?= htmlspecialchars((string) ($old['proveedor_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="d-flex justify-content-between">
            <a href="/compras" class="btn btn-secondary">Volver</a>
            <button class="btn btn-primary">Crear</button>
        </div>
    </form>
</div>
</body>
</html>