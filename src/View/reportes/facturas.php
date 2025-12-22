<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Facturas - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Reporte de Facturas</h1>
        <a href="/" class="btn btn-outline-secondary btn-sm">Volver</a>
    </div>

    <?php if (!empty($errors ?? [])): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars((string) $err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="card card-body mb-3" method="get" action="/reportes/facturas">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars((string) ($desde ?? '')) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars((string) ($hasta ?? '')) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <?php $estadoSel = (string) ($estado ?? ''); ?>
                    <option value="" <?= $estadoSel === '' ? 'selected' : '' ?>>Todos</option>
                    <option value="BORRADOR" <?= $estadoSel === 'BORRADOR' ? 'selected' : '' ?>>BORRADOR</option>
                    <option value="EMITIDA" <?= $estadoSel === 'EMITIDA' ? 'selected' : '' ?>>EMITIDA</option>
                    <option value="PAGADA" <?= $estadoSel === 'PAGADA' ? 'selected' : '' ?>>PAGADA</option>
                    <option value="ANULADA" <?= $estadoSel === 'ANULADA' ? 'selected' : '' ?>>ANULADA</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                <a class="btn btn-outline-secondary w-100" href="/reportes/facturas">Reset</a>
            </div>
        </div>
    </form>

    <?php
        $k = $kpis ?? ['total_registros' => 0, 'suma_total' => 0, 'conteo_por_estado' => []];
        $conteo = $k['conteo_por_estado'] ?? [];
    ?>
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card card-body">
                <div class="text-muted">Total registros</div>
                <div class="fs-4 fw-semibold"><?= (int) ($k['total_registros'] ?? 0) ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <div class="text-muted">Suma total</div>
                <div class="fs-4 fw-semibold">$ <?= number_format((float) ($k['suma_total'] ?? 0), 2) ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <div class="text-muted mb-2">Conteo por estado</div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-secondary">BORRADOR: <?= (int) ($conteo['BORRADOR'] ?? 0) ?></span>
                    <span class="badge text-bg-primary">EMITIDA: <?= (int) ($conteo['EMITIDA'] ?? 0) ?></span>
                    <span class="badge text-bg-success">PAGADA: <?= (int) ($conteo['PAGADA'] ?? 0) ?></span>
                    <span class="badge text-bg-danger">ANULADA: <?= (int) ($conteo['ANULADA'] ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th class="text-end">Total</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach (($rows ?? []) as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($f['numero'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string) ($f['fecha'] ?? '')) ?></td>
                            <td>
                                <?php if (!empty($f['cliente_nombre'])): ?>
                                    <?= htmlspecialchars((string) $f['cliente_nombre']) ?>
                                <?php else: ?>
                                    #<?= (int) ($f['cliente_id'] ?? 0) ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">$ <?= number_format((float) ($f['total'] ?? 0), 2) ?></td>
                            <td><?= htmlspecialchars((string) ($f['estado'] ?? '')) ?></td>
                            <td class="text-end">
                                <a href="/facturas/detalle/<?= (int) ($f['id'] ?? 0) ?>" class="btn btn-outline-secondary btn-sm">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($rows ?? [])): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay resultados para los filtros seleccionados.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>