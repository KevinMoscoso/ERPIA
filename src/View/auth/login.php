<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login - ERP-IA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        crossorigin="anonymous"
    >
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h1 class="h4 text-center mb-4">ERP-IA</h1>
                    <p class="text-center text-muted mb-4">Iniciar sesión</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/auth/authenticate">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary">
                                Entrar
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <p class="text-center text-muted mt-3 small">
                ERP-IA · Proyecto de tesis
            </p>

        </div>
    </div>
</div>

</body>
</html>