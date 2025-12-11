<?php

namespace Erpia\Controller;

class HomeController
{
    public function index(): void
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <title>ERP-IA</title>';
        echo '</head>';
        echo '<body>';
        echo '    <h1>ERP-IA</h1>';
        echo '    <p>Proyecto base PHP generado con soporte de IA.</p>';
        echo '</body>';
        echo '</html>';
    }
}