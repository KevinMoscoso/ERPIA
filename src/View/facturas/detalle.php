<?php
/** @var array $factura */
/** @var array $detalles */
/** @var string|null $error */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle de Factura - ERP-IA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">

    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">
            Factura <?= htmlspecialchars((string) ($factura['numero'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
        </h1>
        <a class="btn btn-outline-secondary" href="/facturas">Volver a facturas</a>
    </div>

    <!-- Errores -->
    <?php if ($error === 'stock'): ?>
        <div class="alert alert-danger">
            Stock insuficiente para registrar esta salida.
        </div>
    <?php endif; ?>

    <!-- Info factura -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <strong>Fecha:</strong>
                    <?= htmlspecialchars((string) $factura['fecha'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="col-md-4">
                    <strong>Cliente ID:</strong>
                    <?= (int) $factura['cliente_id'] ?>
                </div>
                <div class="col-md-4">
                    <strong>Estado:</strong>
                    <span class="badge bg-secondary">
                        <?= htmlspecialchars((string) $factura['estado'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles -->
    <h2 class="h5 mb-2">Detalle</h2>
    <div class="table-responsive mb-4">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>Producto</th>
                <th class="text-end">Cantidad</th>
                <th class="text-end">Precio Unitario</th>
                <th class="text-end">Subtotal</th>
                <th style="width: 120px;">Acciones</th>
            </tr>
            </thead>
            <tbody>

            <?php if (empty($detalles)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No hay productos agregados.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($detalles as $d): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars((string) $d['producto_nombre'], ENT_QUOTES, 'UTF-8') ?>
                            <div class="text-muted small">ID: <?= (int) $d['producto_id'] ?></div>
                        </td>
                        <td class="text-end"><?= (int) $d['cantidad'] ?></td>
                        <td class="text-end"><?= number_format((float) $d['precio_unitario'], 2) ?></td>
                        <td class="text-end"><?= number_format((float) $d['subtotal'], 2) ?></td>
                        <td>
                            <?php if (($factura['estado'] ?? '') === 'BORRADOR'): ?>
                                <a class="btn btn-sm btn-outline-danger"
                                   href="/facturas/eliminarDetalle/<?= (int) $d['id'] ?>"
                                   onclick="return confirm('¿Eliminar este detalle?');">
                                    Eliminar
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">Bloqueado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <!-- Agregar producto -->
    <?php if (($factura['estado'] ?? '') === 'BORRADOR'): ?>
        <h2 class="h5 mb-2">Agregar producto</h2>
        <div class="card">
            <div class="card-body">
                <form method="post"
                      action="/facturas/agregarDetalle/<?= (int) $factura['id'] ?>"
                      class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Producto ID</label>
                        <input type="number" name="producto_id" class="form-control" required min="1">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control" required min="1">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Precio Unitario</label>
                        <input type="number" name="precio_unitario" class="form-control"
                               required min="0.01" step="0.01">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No se pueden agregar productos: la factura no está en estado BORRADOR.
        </div>
    <?php endif; ?>

</div>
</body>
</html>