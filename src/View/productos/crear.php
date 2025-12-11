<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container my-4">
    <h1 class="h3 mb-3">Crear producto</h1>

    <form action="/productos/guardar" method="POST" class="row g-3">

        <div class="col-12">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="col-12 d-flex justify-content-between">
            <a href="/productos" class="btn btn-secondary">Volver al listado</a>
            <button type="submit" class="btn btn-primary">Guardar producto</button>
        </div>
    </form>
</div>
</body>
</html>
