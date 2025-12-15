<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Últimos movimientos de inventario</h1>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Referencia</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($movimientos as $m): ?>
            <tr>
                <td><?= $m['producto_id'] ?></td>
                <td><?= $m['tipo'] ?></td>
                <td><?= $m['cantidad'] ?></td>
                <td><?= $m['referencia_tipo'] ?> <?= $m['referencia_id'] ?></td>
                <td><?= $m['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>