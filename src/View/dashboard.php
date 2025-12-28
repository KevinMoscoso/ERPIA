<?php
/** @var array $user */
?>
<div class="text-center">
    <h1 class="display-4">Bienvenido, <?= htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="lead">Selecciona un módulo desde el menú superior.</p>
</div>