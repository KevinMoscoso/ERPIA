<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Compra
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    public static function getAll(?string $numero = null): array
    {
        $sql = "SELECT c.*, pr.nombre AS proveedor_nombre
                FROM compras c
                LEFT JOIN proveedores pr ON pr.id = c.proveedor_id";

        $params = [];
        if ($numero !== null && trim($numero) !== '') {
            $sql .= " WHERE c.numero LIKE :numero";
            $params[':numero'] = '%' . trim($numero) . '%';
        }

        $sql .= " ORDER BY c.id DESC";

        $stmt = self::db()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(int $id): ?array
    {
        $sql = "SELECT c.*, pr.nombre AS proveedor_nombre
                FROM compras c
                LEFT JOIN proveedores pr ON pr.id = c.proveedor_id
                WHERE c.id = :id
                LIMIT 1";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $sql = "INSERT INTO compras (numero, fecha, proveedor_id, total, created_at)
                VALUES (:numero, :fecha, :proveedor_id, 0.00, NOW())";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':numero', (string) $data['numero'], PDO::PARAM_STR);
        $stmt->bindValue(':fecha', (string) $data['fecha'], PDO::PARAM_STR);

        $proveedorId = isset($data['proveedor_id']) ? (int) $data['proveedor_id'] : 0;
        if ($proveedorId > 0) {
            $stmt->bindValue(':proveedor_id', $proveedorId, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':proveedor_id', null, PDO::PARAM_NULL);
        }

        $stmt->execute();

        return (int) self::db()->lastInsertId();
    }

    public static function recalcularTotal(int $compraId): void
    {
        $sql = "UPDATE compras
                SET total = (
                    SELECT IFNULL(SUM(subtotal), 0)
                    FROM compra_detalles
                    WHERE compra_id = :compra_id_sub
                )
                WHERE id = :compra_id_main";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':compra_id_sub', $compraId, PDO::PARAM_INT);
        $stmt->bindValue(':compra_id_main', $compraId, PDO::PARAM_INT);
        $stmt->execute();
    }
}