<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ajustar inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-4">

    <?php
    // Manejo simple de mensajes de error por query string
    $error = $_GET['error'] ?? '';

    $messages = [
        'stock' => '❌ Operación inválida: el ajuste dejaría el inventario en negativo.',
    ];
    ?>

    <?php if (isset($messages[$error])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($messages[$error], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <h1 class="h4 mb-3">Ajuste de stock - Producto <?= (int) $productoId ?></h1>

    <form method="post" action="/inventario/guardarAjuste/<?= (int) $productoId ?>">
        <div class="mb-3">
            <label class="form-label">Cantidad (+ / -)</label>
            <input
                type="number"
                name="cantidad"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Observación</label>
            <input
                type="text"
                name="observacion"
                class="form-control"
            >
        </div>

        <button class="btn btn-primary">
            Guardar ajuste
        </button>

        <a href="/inventario/producto/<?= (int) $productoId ?>"
           class="btn btn-outline-secondary ms-2">
            Cancelar
        </a>
    </form>

</div>

</body>
</html>