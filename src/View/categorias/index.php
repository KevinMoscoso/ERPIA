<?php
/** @var array $categorias */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Categorías - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Listado de categorías</h1>
        <a href="/categorias/crear" class="btn btn-primary">Crear categoría</a>
    </div>

    <?php if (!empty($categorias)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Creado</th>
                    <th scope="col" class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $categoria['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $categoria['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($categoria['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $categoria['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center">
                            <a href="/categorias/editar/<?= htmlspecialchars((string) $categoria['id'], ENT_QUOTES, 'UTF-8') ?>"
                               class="btn btn-sm btn-warning me-1">
                                Editar
                            </a>
                            <a href="/categorias/eliminar/<?= htmlspecialchars((string) $categoria['id'], ENT_QUOTES, 'UTF-8') ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">
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
            No hay categorías registradas.
        </div>
    <?php endif; ?>
</div>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
></script>
</body>
</html>