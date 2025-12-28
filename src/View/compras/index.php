<?php
/** @var array $compras */
/** @var string $numero */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Compras - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Compras</h1>
        <a href="/compras/crear" class="btn btn-primary">Crear compra</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form class="row g-2" method="get" action="/compras">
                <div class="col-sm-6 col-md-4">
                    <input type="text" name="numero" class="form-control" placeholder="Buscar por número" value="<?= htmlspecialchars((string) $numero, ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-sm-6 col-md-3">
                    <button class="btn btn-outline-secondary w-100" type="submit">Buscar</button>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a class="btn btn-outline-secondary w-100" href="/compras">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead>
        <tr>
            <th>Número</th>
            <th>Fecha</th>
            <th>Proveedor</th>
            <th>Total</th>
            <th class="text-center">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($compras)): ?>
            <?php foreach ($compras as $c): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $c['numero'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $c['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) ($c['proveedor_nombre'] ?? ($c['proveedor_id'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format((float) ($c['total'] ?? 0), 2) ?></td>
                    <td class="text-center">
                        <a href="/compras/detalle/<?= (int) $c['id'] ?>" class="btn btn-sm btn-outline-primary">Detalle</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center py-4">No hay compras para mostrar.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="/" class="btn btn-secondary mt-3">Volver</a>
</div>
</body>
</html>