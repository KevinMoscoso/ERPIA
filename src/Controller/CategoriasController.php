<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Model\Categoria;

class CategoriasController extends Controller
{
    public function index(): void
    {
        $categorias = Categoria::getAll();

        View::render('categorias/index', [
            'categorias' => $categorias,
        ]);
    }

    public function crear(): void
    {
        View::render('categorias/crear', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function guardar(): void
    {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));

        $errors = [];

        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }

        if (!empty($errors)) {
            View::render('categorias/crear', [
                'errors' => $errors,
                'old' => [
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                ],
            ]);

            return;
        }

        Categoria::create([
            'nombre' => $nombre,
            'descripcion' => $descripcion !== '' ? $descripcion : null,
        ]);

        header('Location: /categorias');
        exit;
    }

    public function editar($id): void
    {
        $id = (int) $id;
        $categoria = Categoria::findById($id);

        if ($categoria === null) {
            throw new \RuntimeException('Categoría no encontrada.');
        }

        View::render('categorias/editar', [
            'categoria' => $categoria,
            'errors' => [],
        ]);
    }

    public function actualizar($id): void
    {
        $id = (int) $id;
        $categoria = Categoria::findById($id);

        if ($categoria === null) {
            throw new \RuntimeException('Categoría no encontrada.');
        }

        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));

        $errors = [];

        if ($nombre === '') {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }

        if (!empty($errors)) {
            $categoria['nombre'] = $nombre;
            $categoria['descripcion'] = $descripcion;

            View::render('categorias/editar', [
                'categoria' => $categoria,
                'errors' => $errors,
            ]);

            return;
        }

        Categoria::update($id, [
            'nombre' => $nombre,
            'descripcion' => $descripcion !== '' ? $descripcion : null,
        ]);

        header('Location: /categorias');
        exit;
    }

    public function eliminar($id): void
    {
        $id = (int) $id;

        Categoria::delete($id);

        header('Location: /categorias');
        exit;
    }
}
