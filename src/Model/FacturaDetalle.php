<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class FacturaDetalle
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    public static function getByFactura(int $facturaId): array
    {
        $stmt = self::db()->prepare("
            SELECT 
                fd.id,
                fd.factura_id,
                fd.producto_id,
                fd.cantidad,
                fd.precio_unitario,
                fd.subtotal,
                p.nombre AS producto_nombre
            FROM factura_detalles fd
            LEFT JOIN productos p ON p.id = fd.producto_id
            WHERE fd.factura_id = :factura_id
            ORDER BY fd.id ASC
        ");

        $stmt->bindValue(':factura_id', $facturaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM factura_detalles WHERE id = :id";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $detalle = $stmt->fetch(PDO::FETCH_ASSOC);

        return $detalle ?: null;
    }

    public static function getByFacturaId(int $facturaId): array
    {
        $sql = "SELECT fd.*, p.nombre AS producto_nombre
                FROM factura_detalles fd
                LEFT JOIN productos p ON p.id = fd.producto_id
                WHERE fd.factura_id = :factura_id
                ORDER BY fd.id ASC";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':factura_id', $facturaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function create(array $data): bool
    {
        $subtotal = (int) $data['cantidad'] * (float) $data['precio_unitario'];

        $sql = "INSERT INTO factura_detalles
                (factura_id, producto_id, cantidad, precio_unitario, subtotal, created_at)
                VALUES
                (:factura_id, :producto_id, :cantidad, :precio_unitario, :subtotal, NOW())";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':factura_id', (int) $data['factura_id'], PDO::PARAM_INT);
        $stmt->bindValue(':producto_id', (int) $data['producto_id'], PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', (int) $data['cantidad'], PDO::PARAM_INT);
        $stmt->bindValue(':precio_unitario', (float) $data['precio_unitario']);
        $stmt->bindValue(':subtotal', $subtotal);

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM factura_detalles WHERE id = :id";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}