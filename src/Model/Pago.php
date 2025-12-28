<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Pago
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    public static function getByFacturaId(int $facturaId): array
    {
        $sql = "SELECT * FROM pagos WHERE factura_id = :factura_id ORDER BY fecha ASC";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':factura_id', $facturaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function getTotalPagado(int $facturaId): float
    {
        $sql = "SELECT SUM(monto) FROM pagos WHERE factura_id = :factura_id";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':factura_id', $facturaId, PDO::PARAM_INT);
        $stmt->execute();

        return (float) ($stmt->fetchColumn() ?? 0);
    }

    public static function create(array $data): bool
    {
        $sql = "INSERT INTO pagos (factura_id, monto, metodo, fecha, created_at)
                VALUES (:factura_id, :monto, :metodo, :fecha, NOW())";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':factura_id', (int) $data['factura_id'], PDO::PARAM_INT);
        $stmt->bindValue(':monto', (float) $data['monto']);
        $stmt->bindValue(':metodo', $data['metodo'], PDO::PARAM_STR);
        $stmt->bindValue(':fecha', $data['fecha'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM pagos WHERE id = :id";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}