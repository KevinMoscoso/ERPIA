<?php

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Model\Producto;

class ProductosController
{
    public function index()
    {
        $productos = Producto::getAll();
        View::render('productos/index', ['productos' => $productos]);
    }

    public function crear()
    {
        View::render('productos/crear');
    }

    public function guardar()
    {
        Producto::create($_POST);
        header("Location: /productos");
        exit;
    }

    public function editar($id)
    {
        $producto = Producto::findById((int)$id);
        View::render('productos/editar', ['producto' => $producto]);
    }

    public function actualizar($id)
    {
        Producto::update((int)$id, $_POST);
        header("Location: /productos");
        exit;
    }

    public function eliminar($id)
    {
        Producto::delete((int)$id);
        header("Location: /productos");
        exit;
    }
}
