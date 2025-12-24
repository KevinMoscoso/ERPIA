<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Dashboard - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php use Erpia\Core\Auth; ?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4">Panel principal</h1>
        <a href="/logout" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
    </div>

    <div class="alert alert-success">
        Bienvenido <strong><?= htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>
    </div>

    <?php
        // Helper local para permisos (compatible has / can)
        $has = function (string $perm): bool {
            if (method_exists(\Erpia\Core\Auth::class, 'has')) {
                return Auth::has($perm);
            }
            return Auth::can($perm, false);
        };

        $k = $kpis ?? [
            'facturacion_hoy' => 0,
            'facturacion_mes' => 0,
            'facturas_pendientes' => 0,
            'stock_bajo' => [],
            'compras_recientes' => [],
        ];
    ?>

    <!-- KPIs -->
    <div class="row g-3 mb-4">

        <?php if ($has('facturas.ver')): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted">Total facturado hoy</div>
                        <div class="fs-4 fw-semibold">
                            $ <?= number_format((float)$k['facturacion_hoy'], 2) ?>
                        </div>
                        <div class="small text-muted">EMITIDA / PAGADA</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted">Total facturado este mes</div>
                        <div class="fs-4 fw-semibold">
                            $ <?= number_format((float)$k['facturacion_mes'], 2) ?>
                        </div>
                        <div class="small text-muted">Mes actual</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted">Facturas pendientes</div>
                        <div class="fs-4 fw-semibold">
                            <?= (int)$k['facturas_pendientes'] ?>
                        </div>
                        <div class="small text-muted">Estado EMITIDA</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($has('inventario.ver')): ?>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted">Productos con stock bajo (≤ 5)</div>
                            <a href="/inventario" class="btn btn-outline-secondary btn-sm">Ver</a>
                        </div>

                        <?php if (empty($k['stock_bajo'])): ?>
                            <div class="text-muted">No hay productos con stock bajo.</div>
                        <?php else: ?>
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-end">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($k['stock_bajo'] as $p): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-end fw-semibold"><?= (int)$p['stock'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($has('compras.ver')): ?>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted">Compras recientes</div>
                            <a href="/compras" class="btn btn-outline-secondary btn-sm">Ver</a>
                        </div>

                        <?php if (empty($k['compras_recientes'])): ?>
                            <div class="text-muted">No hay compras recientes.</div>
                        <?php else: ?>
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Proveedor</th>
                                        <th class="text-end">Total</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($k['compras_recientes'] as $c): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($c['numero'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <?= htmlspecialchars(
                                                    $c['proveedor_nombre'] ?? '#'.$c['proveedor_id'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </td>
                                            <td class="text-end">
                                                $ <?= number_format((float)$c['total'], 2) ?>
                                            </td>
                                            <td><?= htmlspecialchars($c['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- MÓDULOS PRINCIPALES -->
    <div class="row g-3 mb-3">

        <?php if ($has('facturas.ver')): ?>
            <div class="col-md-3">
                <a href="/facturas" class="btn btn-primary w-100">Facturas</a>
            </div>

            <div class="col-md-3">
                <a href="/clientes" class="btn btn-primary w-100">Clientes</a>
            </div>

            <div class="col-md-3">
                <a href="/reportes/facturas" class="btn btn-outline-secondary w-100">
                    📊 Reporte Facturas
                </a>
            </div>
        <?php endif; ?>

        <?php if ($has('inventario.ver')): ?>
            <div class="col-md-3">
                <a href="/inventario" class="btn btn-primary w-100">Inventario</a>
            </div>
        <?php endif; ?>

        <?php if ($has('compras.ver')): ?>
            <div class="col-md-3">
                <a href="/compras" class="btn btn-primary w-100">Compras</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- BLOQUE ADMIN -->
    <?php if ($has('usuarios.gestionar') || $has('roles.gestionar')): ?>
        <hr>

        <div class="d-flex flex-wrap gap-2 mt-3">

            <?php if ($has('usuarios.gestionar')): ?>
                <a href="/usuarios" class="btn btn-outline-primary">
                    👤 Usuarios
                </a>
            <?php endif; ?>

            <?php if ($has('roles.gestionar')): ?>
                <a href="/roles" class="btn btn-outline-secondary">
                    🔐 Roles y permisos
                </a>
            <?php endif; ?>

            <a href="/auditoria" class="btn btn-outline-dark">
                🕵 Auditoría
            </a>

        </div>
    <?php endif; ?>

</div>

</body>
</html>