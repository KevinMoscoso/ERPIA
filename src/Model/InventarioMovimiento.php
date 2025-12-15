<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class InventarioMovimiento
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    public static function registrarMovimiento(array $data): bool
    {
        $sql = "INSERT INTO inventario_movimientos
                (producto_id, tipo, cantidad, referencia_tipo, referencia_id, observacion, created_at)
                VALUES
                (:producto_id, :tipo, :cantidad, :referencia_tipo, :referencia_id, :observacion, NOW())";

        $stmt = self::db()->prepare($sql);

        $stmt->bindValue(':producto_id', (int)$data['producto_id'], PDO::PARAM_INT);
        $stmt->bindValue(':tipo', (string)$data['tipo'], PDO::PARAM_STR);
        $stmt->bindValue(':cantidad', (int)$data['cantidad'], PDO::PARAM_INT);
        $stmt->bindValue(':referencia_tipo', (string)$data['referencia_tipo'], PDO::PARAM_STR);

        // ✅ referencia_id puede ser NULL
        if ($data['referencia_id'] === null) {
            $stmt->bindValue(':referencia_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':referencia_id', (int)$data['referencia_id'], PDO::PARAM_INT);
        }

        // ✅ observacion puede ser NULL
        $obs = $data['observacion'] ?? null;
        if ($obs === null || $obs === '') {
            $stmt->bindValue(':observacion', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':observacion', (string)$obs, PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public static function ajustarStock(int $productoId, int $delta): void
    {
        $sql = "UPDATE productos SET stock = stock + :delta WHERE id = :id";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':delta', $delta, PDO::PARAM_INT);
        $stmt->bindValue(':id', $productoId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function registrarSalidaFactura(int $productoId, int $cantidad, int $facturaId, string $obs = null): void
    {
        self::registrarMovimiento([
            'producto_id' => $productoId,
            'tipo' => 'SALIDA',
            'cantidad' => $cantidad,
            'referencia_tipo' => 'FACTURA',
            'referencia_id' => $facturaId,
            'observacion' => $obs,
        ]);

        self::ajustarStock($productoId, -$cantidad);
    }

    public static function registrarEntradaFactura(int $productoId, int $cantidad, int $facturaId, string $obs = null): void
    {
        self::registrarMovimiento([
            'producto_id' => $productoId,
            'tipo' => 'ENTRADA',
            'cantidad' => $cantidad,
            'referencia_tipo' => 'FACTURA',
            'referencia_id' => $facturaId,
            'observacion' => $obs,
        ]);

        self::ajustarStock($productoId, $cantidad);
    }

    public static function getByProducto(int $productoId): array
    {
        $sql = "SELECT * FROM inventario_movimientos
                WHERE producto_id = :id
                ORDER BY id DESC";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $productoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}