<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Usuario - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container py-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Editar usuario</h1>
        <a href="/usuarios" class="btn btn-outline-secondary">Volver</a>
      </div>

      <?php if (!empty($errors['form'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars((string)$errors['form'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <div class="card">
        <div class="card-body">
          <form method="post" action="/usuarios/actualizar/<?= (int)($usuario['id'] ?? 0) ?>" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control <?= !empty($errors['nombre']) ? 'is-invalid' : '' ?>"
                     value="<?= htmlspecialchars((string)($usuario['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
              <?php if (!empty($errors['nombre'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars((string)$errors['nombre'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                     value="<?= htmlspecialchars((string)($usuario['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
              <?php if (!empty($errors['email'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars((string)$errors['email'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-6">
              <label class="form-label">Rol</label>
              <select name="rol_id" class="form-select <?= !empty($errors['rol_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">Seleccione...</option>
                <?php foreach (($roles ?? []) as $r): ?>
                  <option value="<?= (int)$r['id'] ?>" <?= ((int)($usuario['rol_id'] ?? 0) === (int)$r['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string)($r['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['rol_id'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars((string)$errors['rol_id'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-6">
              <label class="form-label">Nueva contraseña (opcional)</label>
              <input type="password" name="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>">
              <?php if (!empty($errors['password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars((string)$errors['password'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
              <div class="form-text">Si la dejas vacía, no se modifica.</div>
            </div>

            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo"
                       <?= ((int)($usuario['activo'] ?? 0) === 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="activo">Activo</label>
              </div>
            </div>

            <div class="col-12 d-flex gap-2 flex-wrap">
              <button class="btn btn-primary" type="submit">Guardar cambios</button>
              <a class="btn btn-outline-secondary" href="/usuarios">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>