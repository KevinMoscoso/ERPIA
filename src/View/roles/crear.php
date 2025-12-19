<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Crear Rol</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h1>Crear Rol</h1>

  <form method="post" action="/roles/guardar">
    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input class="form-control" name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>">
      <?php if (!empty($errors['nombre'])): ?>
        <div class="text-danger"><?= $errors['nombre'] ?></div>
      <?php endif; ?>
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="/roles" class="btn btn-secondary">Volver</a>
  </form>
</div>
</body>
</html>