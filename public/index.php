<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Erpia\Controller\HomeController;

$controller = new HomeController();
$controller->index();