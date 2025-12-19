<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Rol</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h1>Editar Rol</h1>

  <form method="post" action="/roles/actualizar/<?= $rol['id'] ?>">
    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input class="form-control" name="nombre" value="<?= htmlspecialchars($rol['nombre']) ?>">
      <?php if (!empty($errors['nombre'])): ?>
        <div class="text-danger"><?= $errors['nombre'] ?></div>
      <?php endif; ?>
    </div>

    <h5>Permisos</h5>
    <?php foreach ($permisos as $p): ?>
      <div class="form-check">
        <input class="form-check-input"
               type="checkbox"
               name="permisos[]"
               value="<?= $p['id'] ?>"
               <?= in_array($p['id'], $permisosRol) ? 'checked' : '' ?>>
        <label class="form-check-label">
          <?= htmlspecialchars($p['clave']) ?>
        </label>
      </div>
    <?php endforeach; ?>

    <div class="mt-3">
      <button class="btn btn-primary">Guardar</button>
      <a href="/roles" class="btn btn-secondary">Volver</a>
    </div>
  </form>
</div>
</body>
</html>