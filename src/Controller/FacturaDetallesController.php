<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Model\Factura;
use Erpia\Model\FacturaDetalle;

class FacturaDetallesController extends Controller
{
    public function index($facturaId): void
    {
        $facturaId = (int) $facturaId;

        $factura = Factura::findById($facturaId);
        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        $detalles = FacturaDetalle::getByFacturaId($facturaId);

        View::render('facturas/detalle', [
            'factura' => $factura,
            'detalles' => $detalles,
        ]);
    }

    public function guardar($facturaId): void
    {
        $facturaId = (int) $facturaId;

        FacturaDetalle::create([
            'factura_id' => $facturaId,
            'producto_id' => (int) ($_POST['producto_id'] ?? 0),
            'cantidad' => (int) ($_POST['cantidad'] ?? 0),
            'precio_unitario' => (float) ($_POST['precio_unitario'] ?? 0),
        ]);

        header('Location: /facturas/' . $facturaId . '/detalle');
        exit;
    }

    public function eliminar($id, $facturaId): void
    {
        FacturaDetalle::delete((int) $id);

        header('Location: /facturas/' . (int) $facturaId . '/detalle');
        exit;
    }
}