<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Roles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h1>Roles</h1>
    <a href="/roles/crear" class="btn btn-primary">Crear rol</a>
  </div>

  <form class="mb-3" method="get">
    <input class="form-control" name="q" placeholder="Buscar rol" value="<?= htmlspecialchars($q ?? '') ?>">
  </form>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($roles as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['nombre']) ?></td>
          <td>
            <a href="/roles/editar/<?= $r['id'] ?>" class="btn btn-sm btn-secondary">Editar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>