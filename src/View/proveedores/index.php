<?php
/** @var array $proveedores */
/** @var string $q */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Proveedores - ERP-IA</title>
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
        <h1 class="h3 mb-0">Listado de proveedores</h1>
        <a href="/proveedores/crear" class="btn btn-primary">Crear proveedor</a>
    </div>

    <!-- 🔍 BUSCADOR POR NOMBRE -->
    <form method="get" action="/proveedores" class="mb-3">
        <div class="input-group">
            <input
                type="text"
                name="q"
                class="form-control"
                placeholder="Buscar proveedor por nombre"
                value="<?= htmlspecialchars($q ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
            <button class="btn btn-primary">Buscar</button>

            <?php if (!empty($q)): ?>
                <a href="/proveedores" class="btn btn-outline-secondary">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if (!empty($proveedores)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($proveedores as $proveedor): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $proveedor['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($proveedor['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($proveedor['telefono'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($proveedor['direccion'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-center">
                            <a href="/proveedores/editar/<?= (int) $proveedor['id'] ?>"
                               class="btn btn-sm btn-warning me-1">
                                Editar
                            </a>
                            <a href="/proveedores/eliminar/<?= (int) $proveedor['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Seguro que deseas eliminar este proveedor?');">
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
            No se encontraron proveedores.
        </div>
    <?php endif; ?>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
></script>
</body>
</html>
