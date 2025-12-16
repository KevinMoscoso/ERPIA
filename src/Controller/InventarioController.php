<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Database;
use Erpia\Model\InventarioMovimiento;
use PDO;

class InventarioController
{
    public function index(): void
    {
        $fecha = trim($_GET['fecha'] ?? '');

        $sql = "
            SELECT 
                im.id,
                im.producto_id,
                p.nombre AS producto_nombre,
                im.tipo,
                im.cantidad,
                im.referencia_tipo,
                im.referencia_id,
                im.observacion,
                im.created_at,
                f.numero AS factura_numero
            FROM inventario_movimientos im
            LEFT JOIN productos p ON p.id = im.producto_id
            LEFT JOIN facturas f 
                ON im.referencia_tipo = 'FACTURA'
               AND im.referencia_id = f.id
            WHERE 1=1
        ";

        $params = [];

        if ($fecha !== '') {
            $sql .= " AND DATE(im.created_at) = :fecha ";
            $params['fecha'] = $fecha;
        }

        $sql .= " ORDER BY im.created_at DESC LIMIT 50";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);

        View::render('inventario/index', [
            'movimientos' => $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [],
            'fecha' => $fecha,
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
        $obs = trim((string) ($_POST['observacion'] ?? ''));

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
            InventarioMovimiento::registrarMovimiento([
                'producto_id'     => $id,
                'tipo'            => 'AJUSTE',
                'cantidad'        => $cantidad, // delta real
                'referencia_tipo' => 'AJUSTE',
                'referencia_id'   => null,
                'observacion'     => $obs,
            ]);

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