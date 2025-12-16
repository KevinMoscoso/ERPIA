<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inventario por producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">

    <!-- 🔙 Volver a inventario -->
    <div class="mb-3">
        <a href="/inventario" class="btn btn-outline-secondary btn-sm">
            ← Volver a inventario
        </a>
    </div>

    <h1 class="h4 mb-3">Movimientos del producto <?= htmlspecialchars((string)$productoId) ?></h1>

    <a href="/inventario/ajustar/<?= htmlspecialchars((string)$productoId) ?>"
       class="btn btn-primary mb-3">
        Ajustar stock
    </a>

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
        <?php if (!empty($movimientos)): ?>
            <?php foreach ($movimientos as $m): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$m['tipo']) ?></td>
                    <td><?= htmlspecialchars((string)$m['cantidad']) ?></td>
                    <td><?= htmlspecialchars((string)($m['observacion'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string)$m['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No hay movimientos para este producto.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>