<?php
/** @var array $cliente */
/** @var array $errors */

$errors = $errors ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar cliente - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        crossorigin="anonymous"
    >
</head>
<body>
<div class="container my-4">
    <h1 class="h3 mb-3">Editar cliente</h1>

    <form action="/clientes/actualizar/<?= htmlspecialchars((string) $cliente['id'], ENT_QUOTES, 'UTF-8') ?>" method="post" novalidate>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control<?= isset($errors['nombre']) ? ' is-invalid' : '' ?>"
                   value="<?= htmlspecialchars((string) $cliente['nombre'], ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>"
                   value="<?= htmlspecialchars((string) $cliente['email'], ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="<?= htmlspecialchars((string) ($cliente['telefono'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <textarea name="direccion" class="form-control"><?= htmlspecialchars((string) ($cliente['direccion'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="/clientes" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>
</body>
</html>