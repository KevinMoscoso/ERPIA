<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Model\Factura;
use Erpia\Model\FacturaDetalle;

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

    public function detalle(int $facturaId)
    {
        $factura = Factura::findById($facturaId);
        $detalles = FacturaDetalle::getByFactura($facturaId);

        View::render('facturas/detalle', [
            'factura' => $factura,
            'detalles' => $detalles
        ]);
    }

    public function detalleGuardar(int $facturaId): void
    {
        $data = [
            'factura_id'      => $facturaId,
            'producto_id'     => (int) ($_POST['producto_id'] ?? 0),
            'cantidad'        => (int) ($_POST['cantidad'] ?? 0),
            'precio_unitario' => (float) ($_POST['precio_unitario'] ?? 0),
        ];

        if ($data['producto_id'] <= 0 || $data['cantidad'] <= 0 || $data['precio_unitario'] <= 0) {
            header("Location: /facturas/detalle/$facturaId");
            exit;
        }

        FacturaDetalle::create($data);

        header("Location: /facturas/detalle/$facturaId");
        exit;
    }

    public function agregarDetalle(int $facturaId): void
    {
        FacturaDetalle::create([
            'factura_id' => $facturaId,
            'producto_id' => (int) $_POST['producto_id'],
            'cantidad' => (int) $_POST['cantidad'],
            'precio_unitario' => (float) $_POST['precio_unitario'],
        ]);

        header("Location: /facturas/detalle/$facturaId");
        exit;
    }

    public function eliminarDetalle(int $detalleId): void
    {
        $detalle = FacturaDetalle::findById($detalleId);

        if ($detalle) {
            FacturaDetalle::delete($detalleId);
            header("Location: /facturas/detalle/" . $detalle['factura_id']);
            exit;
        }

        throw new \RuntimeException('Detalle no encontrado');
    }
}