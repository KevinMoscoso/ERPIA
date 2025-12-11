<?php
/** @var array $errors */
/** @var array $old */

$errors = $errors ?? [];
$old = $old ?? [];

$oldNombre = $old['nombre'] ?? '';
$oldDescripcion = $old['descripcion'] ?? '';
$oldPrecio = $old['precio'] ?? '';
$oldStock = $old['stock'] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Crear producto - ERP-IA</title>
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
    <h1 class="h3 mb-3">Crear producto</h1>

    <form action="/productos/guardar" method="post" novalidate>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input
                type="text"
                class="form-control<?= isset($errors['nombre']) ? ' is-invalid' : '' ?>"
                id="nombre"
                name="nombre"
                value="<?= htmlspecialchars((string) $oldNombre, ENT_QUOTES, 'UTF-8') ?>"
                required
            >
            <?php if (isset($errors['nombre'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['nombre'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea
                class="form-control<?= isset($errors['descripcion']) ? ' is-invalid' : '' ?>"
                id="descripcion"
                name="descripcion"
                rows="3"
                required
            ><?= htmlspecialchars((string) $oldDescripcion, ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php if (isset($errors['descripcion'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['descripcion'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input
                type="number"
                step="0.01"
                class="form-control<?= isset($errors['precio']) ? ' is-invalid' : '' ?>"
                id="precio"
                name="precio"
                value="<?= htmlspecialchars((string) $oldPrecio, ENT_QUOTES, 'UTF-8') ?>"
                required
            >
            <?php if (isset($errors['precio'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['precio'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input
                type="number"
                class="form-control<?= isset($errors['stock']) ? ' is-invalid' : '' ?>"
                id="stock"
                name="stock"
                value="<?= htmlspecialchars((string) $oldStock, ENT_QUOTES, 'UTF-8') ?>"
                required
            >
            <?php if (isset($errors['stock'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['stock'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-between">
            <a href="/productos" class="btn btn-secondary">Volver al listado</a>
            <button type="submit" class="btn btn-primary">Guardar producto</button>
        </div>
    </form>
</div>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
></script>
</body>
</html>
