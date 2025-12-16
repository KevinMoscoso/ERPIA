<?php
/** @var array $facturas */
/** @var string $q */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Facturas - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Facturas</h1>
        <a href="/facturas/crear" class="btn btn-primary">Crear factura</a>
    </div>

    <!-- 🔍 BUSCADOR POR NÚMERO -->
    <form method="get" action="/facturas" class="mb-3">
        <div class="input-group">
            <input
                type="text"
                name="q"
                class="form-control"
                placeholder="Buscar por número de factura"
                value="<?= htmlspecialchars($q ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
            <button class="btn btn-primary">Buscar</button>
            <?php if (!empty($q)): ?>
                <a href="/facturas" class="btn btn-outline-secondary">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Tabla -->
    <table class="table table-bordered table-striped align-middle">
        <thead>
        <tr>
            <th>Número</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($facturas)): ?>
            <?php foreach ($facturas as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['numero']) ?></td>
                    <td><?= htmlspecialchars($f['fecha']) ?></td>
                    <td><?= htmlspecialchars($f['cliente_nombre'] ?? $f['cliente_id']) ?></td>
                    <td><?= number_format((float) $f['total'], 2) ?></td>
                    <td><?= htmlspecialchars($f['estado']) ?></td>
                    <td class="text-center">

                        <a href="/facturas/editar/<?= $f['id'] ?>"
                           class="btn btn-sm btn-warning me-1">
                            Editar
                        </a>

                        <a href="/facturas/eliminar/<?= $f['id'] ?>"
                           class="btn btn-sm btn-danger me-1"
                           onclick="return confirm('¿Eliminar factura?')">
                            Eliminar
                        </a>

                        <a href="/facturas/detalle/<?= $f['id'] ?>"
                           class="btn btn-sm btn-info me-1">
                            Detalle
                        </a>

                        <a href="/pagos/index/<?= $f['id'] ?>"
                           class="btn btn-sm btn-secondary">
                            Pagos
                        </a>

                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center py-4">
                    No se encontraron facturas.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>