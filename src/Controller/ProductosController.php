<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Model\Producto;

class ProductosController extends Controller
{
    protected static array $productos = [];
    protected static int $nextId = 1;

    public function index(): void
    {
        $productos = array_values(self::$productos);

        $this->render('productos/index', [
            'productos' => $productos,
        ]);
    }

    public function crear(): void
    {
        $this->render('productos/crear', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function guardar(): void
    {
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'precio' => $_POST['precio'] ?? '',
            'stock' => $_POST['stock'] ?? '',
        ];

        $errors = $this->validar($data);

        if (!empty($errors)) {
            $this->render('productos/crear', [
                'errors' => $errors,
                'old' => $data,
            ]);

            return;
        }

        $productoData = [
            'id' => self::$nextId++,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => (float) $data['precio'],
            'stock' => (int) $data['stock'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $producto = new Producto($productoData);

        self::$productos[$producto->id] = $producto;

        $this->index();
    }

    public function editar($id): void
    {
        $id = (int) $id;

        if (!isset(self::$productos[$id])) {
            throw new \RuntimeException('Producto no encontrado.');
        }

        $producto = self::$productos[$id];

        $this->render('productos/editar', [
            'producto' => $producto,
            'errors' => [],
        ]);
    }

    public function actualizar($id): void
    {
        $id = (int) $id;

        if (!isset(self::$productos[$id])) {
            throw new \RuntimeException('Producto no encontrado.');
        }

        $producto = self::$productos[$id];

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'precio' => $_POST['precio'] ?? '',
            'stock' => $_POST['stock'] ?? '',
        ];

        $errors = $this->validar($data);

        if (!empty($errors)) {
            $producto->nombre = (string) $data['nombre'];
            $producto->descripcion = (string) $data['descripcion'];
            $producto->precio = (float) $data['precio'];
            $producto->stock = (int) $data['stock'];

            $this->render('productos/editar', [
                'producto' => $producto,
                'errors' => $errors,
            ]);

            return;
        }

        $producto->nombre = (string) $data['nombre'];
        $producto->descripcion = (string) $data['descripcion'];
        $producto->precio = (float) $data['precio'];
        $producto->stock = (int) $data['stock'];

        self::$productos[$producto->id] = $producto;

        $this->index();
    }

    public function eliminar($id): void
    {
        $id = (int) $id;

        if (isset(self::$productos[$id])) {
            unset(self::$productos[$id]);
        }

        $this->index();
    }

    protected function validar(array $data): array
    {
        $errors = [];

        $nombre = trim((string) $data['nombre']);
        $descripcion = trim((string) $data['descripcion']);
        $precio = (string) $data['precio'];
        $stock = (string) $data['stock'];

        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }

        if ($descripcion === '') {
            $errors['descripcion'] = 'La descripción es obligatoria.';
        }

        if ($precio === '' || !is_numeric($precio)) {
            $errors['precio'] = 'El precio debe ser un número válido.';
        } elseif ((float) $precio < 0) {
            $errors['precio'] = 'El precio no puede ser negativo.';
        }

        if ($stock === '' || !ctype_digit($stock)) {
            $errors['stock'] = 'El stock debe ser un número entero válido.';
        } elseif ((int) $stock < 0) {
            $errors['stock'] = 'El stock no puede ser negativo.';
        }

        return $errors;
    }
}
