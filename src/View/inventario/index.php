<?php
$fecha = $fecha ?? '';
$producto = $producto ?? '';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inventario - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Inventario</h1>
        <a href="/inventario" class="btn btn-outline-secondary btn-sm">Actualizar</a>
    </div>

    <!-- 🔍 BUSCADOR -->
    <form method="get" action="/inventario" class="row g-2 mb-3">
        <div class="col-md-3">
            <input
                type="date"
                name="fecha"
                class="form-control"
                value="<?= htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="col-md-4">
            <input
                type="text"
                name="producto"
                class="form-control"
                placeholder="Buscar por producto"
                value="<?= htmlspecialchars($producto, ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="col-md-auto">
            <button class="btn btn-primary">Buscar</button>
            <a href="/inventario" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <div class="card">
        <div class="card-header">
            Últimos movimientos (50)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Stock actual</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Referencia</th>
                        <th>Observación</th>
                        <th class="text-center">Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($movimientos)): ?>
                        <?php foreach ($movimientos as $m): ?>
                            <?php
                            $tipo = (string) $m['tipo'];
                            $badgeClass = 'bg-secondary';

                            if ($tipo === 'ENTRADA') $badgeClass = 'bg-success';
                            elseif ($tipo === 'SALIDA') $badgeClass = 'bg-danger';
                            elseif ($tipo === 'AJUSTE') $badgeClass = 'bg-warning text-dark';

                            if ($m['referencia_tipo'] === 'FACTURA' && !empty($m['factura_numero'])) {
                                $refText = 'FACTURA Nº ' . $m['factura_numero'];
                            } else {
                                $refText = $m['referencia_tipo'];
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($m['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($m['producto_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="fw-semibold">
                                    <?= (int) $m['stock_actual'] ?>
                                </td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $tipo ?></span></td>
                                <td><?= (int) $m['cantidad'] ?></td>
                                <td><?= htmlspecialchars($refText, ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars((string) $m['observacion'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="/inventario/producto/<?= (int) $m['producto_id'] ?>">
                                        Ver por producto
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No hay movimientos para mostrar.
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