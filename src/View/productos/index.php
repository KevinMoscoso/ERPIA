<?php
/** @var array $productos */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Productos - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Listado de productos</h1>
        <a href="/productos/crear" class="btn btn-primary">Crear producto</a>
    </div>

    <?php if (!empty($productos)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Creado</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format($producto['precio'], 2, '.', '') ?></td>
                        <td><?= htmlspecialchars($producto['stock'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($producto['created_at'], ENT_QUOTES, 'UTF-8') ?></td>

                        <td class="text-center">
                            <a href="/productos/editar/<?= $producto['id'] ?>"
                               class="btn btn-sm btn-warning me-1">Editar</a>

                            <a href="/productos/eliminar/<?= $producto['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No hay productos registrados.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
