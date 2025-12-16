<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class CompraDetalle
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    public static function getByCompraId(int $compraId): array
    {
        $sql = "SELECT cd.*, p.nombre AS producto_nombre
                FROM compra_detalles cd
                LEFT JOIN productos p ON p.id = cd.producto_id
                WHERE cd.compra_id = :compra_id
                ORDER BY cd.id ASC";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':compra_id', $compraId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function create(array $data): bool
    {
        $cantidad = (int) ($data['cantidad'] ?? 0);
        $precio = (float) ($data['precio_unitario'] ?? 0);
        $subtotal = $cantidad * $precio;

        $sql = "INSERT INTO compra_detalles
                (compra_id, producto_id, cantidad, precio_unitario, subtotal, created_at)
                VALUES
                (:compra_id, :producto_id, :cantidad, :precio_unitario, :subtotal, NOW())";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':compra_id', (int) $data['compra_id'], PDO::PARAM_INT);
        $stmt->bindValue(':producto_id', (int) $data['producto_id'], PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindValue(':precio_unitario', $precio);
        $stmt->bindValue(':subtotal', $subtotal);

        return $stmt->execute();
    }
}