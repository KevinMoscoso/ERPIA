<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Database;
use Erpia\Model\InventarioMovimiento;

class InventarioController
{
    public function index(): void
    {
        $stmt = Database::getConnection()->query(
            "SELECT * FROM inventario_movimientos ORDER BY id DESC LIMIT 50"
        );

        View::render('inventario/index', [
            'movimientos' => $stmt->fetchAll(),
        ]);
    }

    public function producto(int $id): void
    {
        $movimientos = InventarioMovimiento::getByProducto($id);

        View::render('inventario/producto', [
            'movimientos' => $movimientos,
            'productoId' => $id,
        ]);
    }

    public function ajustar(int $id): void
    {
        View::render('inventario/ajustar', ['productoId' => $id]);
    }

    public function guardarAjuste(int $id): void
    {
        $cantidad = (int) $_POST['cantidad'];
        $obs = $_POST['observacion'] ?? 'Ajuste manual';

        InventarioMovimiento::registrarMovimiento([
            'producto_id' => $id,
            'tipo' => 'AJUSTE',
            'cantidad' => abs($cantidad),
            'referencia_tipo' => 'AJUSTE',
            'referencia_id' => null,
            'observacion' => $obs,
        ]);

        InventarioMovimiento::ajustarStock($id, $cantidad);

        header('Location: /inventario/producto/' . $id);
        exit;
    }
}