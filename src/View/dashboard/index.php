<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Dashboard - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4">Panel principal</h1>
        <a href="/logout" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
    </div>

    <div class="alert alert-success">
        Bienvenido <strong><?= htmlspecialchars($user['nombre']) ?></strong>
    </div>

    <div class="row g-3">

        <div class="col-md-3">
            <a href="/facturas" class="btn btn-primary w-100">Facturas</a>
        </div>

        <div class="col-md-3">
            <a href="/clientes" class="btn btn-primary w-100">Clientes</a>
        </div>

        <div class="col-md-3">
            <a href="/inventario" class="btn btn-primary w-100">Inventario</a>
        </div>

        <div class="col-md-3">
            <a href="/compras" class="btn btn-primary w-100">Compras</a>
        </div>

    </div>
</div>

</body>
</html>