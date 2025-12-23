<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Auditoría - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">🕵 Auditoría</h1>
        <a href="/" class="btn btn-outline-secondary btn-sm">Volver</a>
    </div>

    <?php if (!empty($errors ?? [])): ?>
        <div class="alert alert-danger">
            <?php foreach (($errors ?? []) as $err): ?>
                <div><?= htmlspecialchars((string)$err, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="card card-body mb-3" method="get" action="/auditoria">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Usuario</label>
                <select name="usuario_id" class="form-select">
                    <?php $uidSel = (int)($usuario_id ?? 0); ?>
                    <option value="">Todos</option>
                    <?php foreach (($usuarios ?? []) as $u): ?>
                        <?php $uid = (int)($u['id'] ?? 0); ?>
                        <option value="<?= $uid ?>" <?= $uidSel === $uid ? 'selected' : '' ?>>
                            #<?= $uid ?> — <?= htmlspecialchars((string)($u['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars((string)($desde ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars((string)($hasta ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-md-1 d-flex flex-column gap-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="/auditoria" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Referencia</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($rows ?? [])): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No hay resultados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach (($rows ?? []) as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars((string)($r['usuario_nombre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars((string)($r['accion'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars((string)($r['referencia'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars((string)($r['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</body>
</html>