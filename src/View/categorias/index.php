<?php
/** @var array $categorias */
/** @var string $q */
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
        crossorigin="anonymous"
    >
</head>
<body>
<div class="container my-4">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Listado de categorías</h1>
        <a href="/categorias/crear" class="btn btn-primary">Crear categoría</a>
    </div>

    <!-- 🔍 BUSCADOR ID / NOMBRE -->
    <form method="get" action="/categorias" class="mb-3">
        <div class="input-group">
            <input
                type="text"
                name="q"
                class="form-control"
                placeholder="Buscar por ID o nombre de categoría"
                value="<?= htmlspecialchars($q ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
            <button class="btn btn-primary">Buscar</button>

            <?php if (!empty($q)): ?>
                <a href="/categorias" class="btn btn-outline-secondary">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if (!empty($categorias)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Creado</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= (int) $categoria['id'] ?></td>
                        <td><?= htmlspecialchars((string) $categoria['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($categoria['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $categoria['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center">
                            <a href="/categorias/editar/<?= (int) $categoria['id'] ?>"
                               class="btn btn-sm btn-warning me-1">
                                Editar
                            </a>
                            <a href="/categorias/eliminar/<?= (int) $categoria['id'] ?>"
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
            No se encontraron categorías.
        </div>
    <?php endif; ?>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
></script>
</body>
</html>