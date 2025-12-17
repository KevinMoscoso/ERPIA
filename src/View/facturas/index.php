<?php
/** @var array $facturas */
/** @var string|null $q */
/** @var string|null $error */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facturas - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">

    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Facturas</h1>
        <a class="btn btn-primary" href="/facturas/crear">Crear factura</a>
    </div>

    <!-- Errores -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-warning">
            No se pudo completar la acción solicitada.
        </div>
    <?php endif; ?>

    <!-- 🔍 Buscador por número -->
    <form class="row g-2 mb-3" method="get" action="/facturas">
        <div class="col-sm-8 col-md-6">
            <input
                type="text"
                class="form-control"
                name="q"
                placeholder="Buscar por número de factura"
                value="<?= htmlspecialchars((string) ($q ?? ''), ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
        </div>
        <div class="col-auto">
            <a class="btn btn-outline-secondary" href="/facturas">Limpiar</a>
        </div>
    </form>

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>Número</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th class="text-end">Total</th>
                <th>Estado</th>
                <th style="width: 340px;">Acciones</th>
            </tr>
            </thead>
            <tbody>

            <?php if (empty($facturas)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No se encontraron facturas.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($facturas as $f): ?>
                    <?php
                    $estado = (string) ($f['estado'] ?? '');
                    $cliente = (string) ($f['cliente_nombre'] ?? '');
                    if ($cliente === '') {
                        $cliente = 'ID: ' . (string) ($f['cliente_id'] ?? '');
                    }

                    $badge = 'bg-secondary';
                    if ($estado === 'EMITIDA') $badge = 'bg-primary';
                    if ($estado === 'PAGADA')  $badge = 'bg-success';
                    if ($estado === 'ANULADA') $badge = 'bg-dark';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $f['numero'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $f['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($cliente, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-end"><?= number_format((float) $f['total'], 2) ?></td>
                        <td>
                            <span class="badge <?= $badge ?>">
                                <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">

                                <?php if ($estado === 'BORRADOR'): ?>
                                    <a class="btn btn-sm btn-outline-secondary"
                                       href="/facturas/editar/<?= (int) $f['id'] ?>">Editar</a>

                                    <a class="btn btn-sm btn-outline-danger"
                                       href="/facturas/eliminar/<?= (int) $f['id'] ?>"
                                       onclick="return confirm('¿Eliminar factura?');">Eliminar</a>

                                    <a class="btn btn-sm btn-outline-info"
                                       href="/facturas/detalle/<?= (int) $f['id'] ?>">Detalle</a>

                                    <a class="btn btn-sm btn-success"
                                       href="/facturas/emitir/<?= (int) $f['id'] ?>"
                                       onclick="return confirm('¿Emitir factura?');">Emitir</a>

                                    <a class="btn btn-sm btn-warning"
                                       href="/facturas/anular/<?= (int) $f['id'] ?>"
                                       onclick="return confirm('¿Anular factura?');">Anular</a>

                                <?php elseif ($estado === 'EMITIDA'): ?>
                                    <a class="btn btn-sm btn-outline-info"
                                       href="/facturas/detalle/<?= (int) $f['id'] ?>">Detalle</a>

                                    <a class="btn btn-sm btn-outline-primary"
                                       href="/pagos/index/<?= (int) $f['id'] ?>">Pagos</a>

                                    <a class="btn btn-sm btn-warning"
                                       href="/facturas/anular/<?= (int) $f['id'] ?>"
                                       onclick="return confirm('¿Anular factura? (solo si no hay pagos)');">Anular</a>

                                <?php elseif ($estado === 'PAGADA'): ?>
                                    <a class="btn btn-sm btn-outline-info"
                                       href="/facturas/detalle/<?= (int) $f['id'] ?>">Detalle</a>

                                    <a class="btn btn-sm btn-outline-primary"
                                       href="/pagos/index/<?= (int) $f['id'] ?>">Pagos</a>

                                <?php else: ?>
                                    <a class="btn btn-sm btn-outline-info"
                                       href="/facturas/detalle/<?= (int) $f['id'] ?>">Detalle</a>
                                <?php endif; ?>

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