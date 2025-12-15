<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ajustar inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Ajuste de stock - Producto <?= $productoId ?></h1>

    <form method="post" action="/inventario/guardarAjuste/<?= $productoId ?>">
        <div class="mb-3">
            <label>Cantidad (+ / -)</label>
            <input type="number" name="cantidad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Observación</label>
            <input type="text" name="observacion" class="form-control">
        </div>

        <button class="btn btn-primary">Guardar ajuste</button>
    </form>
</div>
</body>
</html>