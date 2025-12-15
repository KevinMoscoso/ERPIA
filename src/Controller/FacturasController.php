<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Core\Database;
use Erpia\Model\Factura;
use Erpia\Model\FacturaDetalle;
use Erpia\Model\InventarioMovimiento;

class FacturasController extends Controller
{
    /* =========================
     * LISTADO
     * ========================= */
    public function index(): void
    {
        $facturas = Factura::getAll();
        View::render('facturas/index', ['facturas' => $facturas]);
    }

    /* =========================
     * CREAR FACTURA
     * ========================= */
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
            'numero'     => trim($_POST['numero'] ?? ''),
            'fecha'      => $_POST['fecha'] ?? '',
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'estado'     => $_POST['estado'] ?? 'BORRADOR',
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

    /* =========================
     * EDITAR FACTURA
     * ========================= */
    public function editar(int $id): void
    {
        $factura = Factura::findById($id);

        if (!$factura) {
            throw new \RuntimeException('Factura no encontrada');
        }

        View::render('facturas/editar', [
            'factura' => $factura,
            'errors' => [],
        ]);
    }

    public function actualizar(int $id): void
    {
        $data = [
            'numero'     => trim($_POST['numero'] ?? ''),
            'fecha'      => $_POST['fecha'] ?? '',
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'estado'     => $_POST['estado'] ?? 'BORRADOR',
        ];

        Factura::update($id, $data);

        header('Location: /facturas');
        exit;
    }

    /* =========================
     * ELIMINAR FACTURA
     * ========================= */
    public function eliminar(int $id): void
    {
        Factura::delete($id);

        header('Location: /facturas');
        exit;
    }

    /* =========================
     * DETALLE DE FACTURA
     * ========================= */
    public function detalle(int $facturaId): void
    {
        $factura  = Factura::findById($facturaId);
        $detalles = FacturaDetalle::getByFactura($facturaId);

        if (!$factura) {
            throw new \RuntimeException('Factura no encontrada');
        }

        View::render('facturas/detalle', [
            'factura'  => $factura,
            'detalles' => $detalles,
        ]);
    }

    /* =========================
     * AGREGAR PRODUCTO A FACTURA
     * ========================= */
    public function agregarDetalle(int $facturaId): void
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

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // 1️⃣ Crear detalle
            FacturaDetalle::create($data);

            // 2️⃣ Registrar salida de inventario
            InventarioMovimiento::registrarSalidaFactura(
                $data['producto_id'],
                $data['cantidad'],
                $facturaId,
                'Salida por factura ' . $facturaId
            );

            // 3️⃣ Recalcular total
            Factura::recalcularTotal($facturaId);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        header("Location: /facturas/detalle/$facturaId");
        exit;
    }

    /* =========================
     * ELIMINAR DETALLE
     * ========================= */
    public function eliminarDetalle(int $detalleId): void
    {
        $detalle = FacturaDetalle::findById($detalleId);

        if (!$detalle) {
            throw new \RuntimeException('Detalle no encontrado');
        }

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // 1️⃣ Eliminar detalle
            FacturaDetalle::delete($detalleId);

            // 2️⃣ Registrar entrada de inventario
            InventarioMovimiento::registrarEntradaFactura(
                (int) $detalle['producto_id'],
                (int) $detalle['cantidad'],
                (int) $detalle['factura_id'],
                'Reverso por eliminación detalle factura ' . $detalle['factura_id']
            );

            // 3️⃣ Recalcular total
            Factura::recalcularTotal((int) $detalle['factura_id']);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        header("Location: /facturas/detalle/" . $detalle['factura_id']);
        exit;
    }
}