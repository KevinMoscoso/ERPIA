<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Core\Database;
use Erpia\Model\Compra;
use Erpia\Model\CompraDetalle;
use Erpia\Model\InventarioMovimiento;

class ComprasController extends Controller
{
    public function index(): void
    {
        $numero = isset($_GET['numero']) ? (string) $_GET['numero'] : null;
        $compras = Compra::getAll($numero);

        View::render('compras/index', [
            'compras' => $compras,
            'numero' => $numero ?? '',
        ]);
    }

    public function crear(): void
    {
        View::render('compras/crear', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function guardar(): void
    {
        $numero = trim((string) ($_POST['numero'] ?? ''));
        $fecha = (string) ($_POST['fecha'] ?? '');
        $proveedorId = (int) ($_POST['proveedor_id'] ?? 0);

        if ($numero === '' || $fecha === '') {
            View::render('compras/crear', [
                'errors' => ['form' => 'Datos inválidos'],
                'old' => [
                    'numero' => $numero,
                    'fecha' => $fecha,
                    'proveedor_id' => $proveedorId > 0 ? $proveedorId : '',
                ],
            ]);
            return;
        }

        $compraId = Compra::create([
            'numero' => $numero,
            'fecha' => $fecha,
            'proveedor_id' => $proveedorId > 0 ? $proveedorId : null,
        ]);

        header('Location: /compras/detalle/' . $compraId);
        exit;
    }

    public function detalle($id): void
    {
        $compraId = (int) $id;
        $compra = Compra::findById($compraId);

        if ($compra === null) {
            throw new \RuntimeException('Compra no encontrada.');
        }

        $detalles = CompraDetalle::getByCompraId($compraId);

        View::render('compras/detalle', [
            'compra' => $compra,
            'detalles' => $detalles,
        ]);
    }

    public function guardarDetalle($id): void
    {
        $compraId = (int) $id;
        $compra = Compra::findById($compraId);

        if ($compra === null) {
            throw new \RuntimeException('Compra no encontrada.');
        }

        $productoId = (int) ($_POST['producto_id'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 0);
        $precio = (float) ($_POST['precio_unitario'] ?? 0);

        if ($productoId <= 0 || $cantidad <= 0 || $precio < 0) {
            header('Location: /compras/detalle/' . $compraId . '?error=datos');
            exit;
        }

        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            CompraDetalle::create([
                'compra_id' => $compraId,
                'producto_id' => $productoId,
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
            ]);

            InventarioMovimiento::registrarMovimiento([
                'producto_id' => $productoId,
                'tipo' => 'ENTRADA',
                'cantidad' => $cantidad,
                'referencia_tipo' => 'COMPRA',
                'referencia_id' => $compraId,
                'observacion' => 'Entrada por compra ' . ($compra['numero'] ?? (string) $compraId),
            ]);

            InventarioMovimiento::ajustarStock($productoId, $cantidad);

            Compra::recalcularTotal($compraId);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        header('Location: /compras/detalle/' . $compraId);
        exit;
    }
}