<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Dashboard - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php use Erpia\Core\Auth; ?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4">Panel principal</h1>
        <a href="/logout" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
    </div>

    <div class="alert alert-success">
        Bienvenido <strong><?= htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>
    </div>

    <!-- MÓDULOS PRINCIPALES -->
    <div class="row g-3 mb-3">

        <?php if (Auth::has('facturas.ver')): ?>
            <div class="col-md-3">
                <a href="/facturas" class="btn btn-primary w-100">Facturas</a>
            </div>

            <div class="col-md-3">
                <a href="/clientes" class="btn btn-primary w-100">Clientes</a>
            </div>

            <div class="col-md-3">
                <a href="/reportes/facturas" class="btn btn-outline-secondary w-100">
                    📊 Reporte Facturas
                </a>
            </div>
        <?php endif; ?>

        <?php if (Auth::has('inventario.ver')): ?>
            <div class="col-md-3">
                <a href="/inventario" class="btn btn-primary w-100">Inventario</a>
            </div>
        <?php endif; ?>

        <?php if (Auth::has('compras.ver')): ?>
            <div class="col-md-3">
                <a href="/compras" class="btn btn-primary w-100">Compras</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- BLOQUE ADMIN -->
    <?php if (Auth::has('usuarios.gestionar') || Auth::has('roles.gestionar')): ?>
        <hr>

        <div class="d-flex flex-wrap gap-2 mt-3">

            <?php if (Auth::has('usuarios.gestionar')): ?>
                <a href="/usuarios" class="btn btn-outline-primary">
                    👤 Usuarios
                </a>
            <?php endif; ?>

            <?php if (Auth::has('roles.gestionar')): ?>
                <a href="/roles" class="btn btn-outline-secondary">
                    🔐 Roles y permisos
                </a>
            <?php endif; ?>

        </div>
    <?php endif; ?>

</div>

</body>
</html>