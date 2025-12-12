<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Model\Factura;

class FacturasController extends Controller
{
    public function index(): void
    {
        $facturas = Factura::getAll();

        View::render('facturas/index', [
            'facturas' => $facturas,
        ]);
    }

    public function crear(): void
    {
        View::render('facturas/crear', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function guardar(): void
    {
        $data = [
            'numero' => trim($_POST['numero'] ?? ''),
            'fecha' => $_POST['fecha'] ?? '',
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'total' => (float) ($_POST['total'] ?? 0),
            'estado' => $_POST['estado'] ?? 'BORRADOR',
        ];

        if ($data['numero'] === '' || $data['fecha'] === '' || $data['cliente_id'] <= 0) {
            View::render('facturas/crear', [
                'errors' => ['form' => 'Datos inválidos'],
                'old' => $data,
            ]);
            return;
        }

        Factura::create($data);

        header('Location: /facturas');
        exit;
    }

    public function editar($id): void
    {
        $factura = Factura::findById((int) $id);

        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        View::render('facturas/editar', [
            'factura' => $factura,
            'errors' => [],
        ]);
    }

    public function actualizar($id): void
    {
        $data = [
            'numero' => trim($_POST['numero'] ?? ''),
            'fecha' => $_POST['fecha'] ?? '',
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'total' => (float) ($_POST['total'] ?? 0),
            'estado' => $_POST['estado'] ?? 'BORRADOR',
        ];

        if ($data['numero'] === '' || $data['fecha'] === '' || $data['cliente_id'] <= 0) {
            $factura = Factura::findById((int) $id);
            $factura = array_merge($factura ?? [], $data);

            View::render('facturas/editar', [
                'factura' => $factura,
                'errors' => ['form' => 'Datos inválidos'],
            ]);
            return;
        }

        Factura::update((int) $id, $data);

        header('Location: /facturas');
        exit;
    }

    public function eliminar($id): void
    {
        Factura::delete((int) $id);

        header('Location: /facturas');
        exit;
    }
}