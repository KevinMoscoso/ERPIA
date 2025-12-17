<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Core\Database;
use Erpia\Core\Auth;
use Erpia\Model\Auditoria;
use Erpia\Model\Compra;
use Erpia\Model\CompraDetalle;
use Erpia\Model\InventarioMovimiento;

class ComprasController extends Controller
{
    private function userId(): int
    {
        $u = Auth::user();
        return (int) ($u['id'] ?? 0);
    }

    public function index(): void
    {
        Auth::can('compras.ver');

        $numero = isset($_GET['numero']) ? (string) $_GET['numero'] : null;
        $compras = Compra::getAll($numero);

        View::render('compras/index', [
            'compras' => $compras,
            'numero' => $numero ?? '',
        ]);
    }

    public function crear(): void
    {
        Auth::can('compras.crear');

        View::render('compras/crear', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function guardar(): void
    {
        Auth::can('compras.crear');

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

        if ((int)$compraId > 0) {
            Auditoria::registrar($this->userId(), 'compra.crear', 'compra:' . (int)$compraId);
        }

        header('Location: /compras/detalle/' . $compraId);
        exit;
    }

    public function detalle($id): void
    {
        Auth::can('compras.ver');

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
        Auth::can('compras.detalle.modificar');

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

            Auditoria::registrar(
                $this->userId(),
                'compra.detalle.agregar',
                'compra:' . $compraId . '|producto:' . $productoId
            );

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        header('Location: /compras/detalle/' . $compraId);
        exit;
    }
}