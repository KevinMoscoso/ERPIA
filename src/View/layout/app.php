<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/dashboard">ERP-IA</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <?php $user = $_SESSION['user'] ?? null; ?>
        
        <?php if ($user): ?>
            <?php if (in_array('facturas.ver', $user['permisos'], true)): ?>
              <li class="nav-item"><a class="nav-link" href="/facturas">Facturas</a></li>
            <?php endif; ?>

            <?php if (in_array('compras.ver', $user['permisos'], true)): ?>
              <li class="nav-item"><a class="nav-link" href="/compras">Compras</a></li>
            <?php endif; ?>

            <?php if (in_array('inventario.ver', $user['permisos'], true)): ?>
              <li class="nav-item"><a class="nav-link" href="/inventario">Inventario</a></li>
            <?php endif; ?>

            <?php if (in_array('usuarios.gestionar', $user['permisos'], true)): ?>
              <li class="nav-item"><a class="nav-link" href="/usuarios">Usuarios</a></li>
            <?php endif; ?>
        <?php endif; ?>
      </ul>

      <?php if ($user): ?>
        <span class="navbar-text text-white me-3">
          <?= htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8') ?>
        </span>
        <a href="/auth/logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container my-4">
    <?= $content ?? '' ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>