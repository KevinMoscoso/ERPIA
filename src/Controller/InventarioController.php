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
        $cantidad = (int) ($_POST['cantidad'] ?? 0);
        $obs = trim((string)($_POST['observacion'] ?? ''));

        if ($cantidad === 0) {
            header('Location: /inventario/ajustar/' . $id);
            exit;
        }

        if ($obs === '') {
            $obs = 'Ajuste manual';
        }

        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            // ✅ Guardar el delta real (puede ser negativo)
            InventarioMovimiento::registrarMovimiento([
                'producto_id' => $id,
                'tipo' => 'AJUSTE',
                'cantidad' => $cantidad,          // ✅ NO abs()
                'referencia_tipo' => 'AJUSTE',
                'referencia_id' => null,
                'observacion' => $obs,
            ]);

            // ✅ Aplicar delta
            InventarioMovimiento::ajustarStock($id, $cantidad);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        header('Location: /inventario/producto/' . $id);
        exit;
    }
}