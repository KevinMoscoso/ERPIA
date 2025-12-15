<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inventario por producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h4 mb-3">Movimientos del producto <?= $productoId ?></h1>

    <a href="/inventario/ajustar/<?= $productoId ?>" class="btn btn-primary mb-3">Ajustar stock</a>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Observación</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($movimientos as $m): ?>
            <tr>
                <td><?= $m['tipo'] ?></td>
                <td><?= $m['cantidad'] ?></td>
                <td><?= $m['observacion'] ?></td>
                <td><?= $m['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>