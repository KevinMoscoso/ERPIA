<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container py-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Usuarios</h1>
        <a href="/usuarios/crear" class="btn btn-primary">Crear usuario</a>
      </div>

      <?php if (($flash ?? null) === 'creado'): ?>
        <div class="alert alert-success">Usuario creado correctamente.</div>
      <?php elseif (($flash ?? null) === 'actualizado'): ?>
        <div class="alert alert-success">Usuario actualizado correctamente.</div>
      <?php elseif (($flash ?? null) === 'toggle'): ?>
        <div class="alert alert-success">Estado del usuario actualizado.</div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="alert alert-warning">Ocurrió un problema al procesar la acción.</div>
      <?php endif; ?>

      <form class="row g-2 mb-3" method="get" action="/usuarios">
        <div class="col-sm-8 col-md-6">
          <input type="text" class="form-control" name="q" placeholder="Buscar por nombre o email" value="<?= htmlspecialchars((string)($q ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="col-auto">
          <button class="btn btn-outline-primary" type="submit">Buscar</button>
        </div>
        <div class="col-auto">
          <a class="btn btn-outline-secondary" href="/usuarios">Limpiar</a>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Rol</th>
              <th>Activo</th>
              <th>Creado</th>
              <th style="width: 240px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($usuarios)): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-4">No se encontraron usuarios.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($usuarios as $u): ?>
                <?php $activo = ((int)($u['activo'] ?? 0)) === 1; ?>
                <tr>
                  <td><?= (int)($u['id'] ?? 0) ?></td>
                  <td><?= htmlspecialchars((string)($u['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($u['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($u['rol_nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td>
                    <?php if ($activo): ?>
                      <span class="badge bg-success">Sí</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">No</span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars((string)($u['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td>
                    <div class="d-flex flex-wrap gap-2">
                      <a class="btn btn-sm btn-outline-secondary" href="/usuarios/editar/<?= (int)$u['id'] ?>">Editar</a>

                      <form method="post" action="/usuarios/toggle/<?= (int)$u['id'] ?>" onsubmit="return confirm('¿Cambiar estado del usuario?');">
                        <button type="submit" class="btn btn-sm <?= $activo ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                          <?= $activo ? 'Desactivar' : 'Activar' ?>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>