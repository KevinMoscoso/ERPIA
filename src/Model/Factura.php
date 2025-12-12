<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Model;
use PDO;

class Factura extends Model
{
    public static function getAll(): array
    {
        $sql = "SELECT f.*, c.nombre AS cliente_nombre
                FROM facturas f
                LEFT JOIN clientes c ON c.id = f.cliente_id
                ORDER BY f.id DESC";

        $stmt = self::db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM facturas WHERE id = :id LIMIT 1";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    public static function create(array $data): bool
    {
        $estado = in_array($data['estado'] ?? 'BORRADOR', ['BORRADOR', 'EMITIDA', 'ANULADA'], true)
            ? $data['estado']
            : 'BORRADOR';

        $sql = "INSERT INTO facturas (numero, fecha, cliente_id, total, estado, created_at)
                VALUES (:numero, :fecha, :cliente_id, :total, :estado, NOW())";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':numero', $data['numero'], PDO::PARAM_STR);
        $stmt->bindValue(':fecha', $data['fecha'], PDO::PARAM_STR);
        $stmt->bindValue(':cliente_id', (int) $data['cliente_id'], PDO::PARAM_INT);
        $stmt->bindValue(':total', (float) $data['total'], PDO::PARAM_STR);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function update(int $id, array $data): bool
    {
        $estado = in_array($data['estado'] ?? 'BORRADOR', ['BORRADOR', 'EMITIDA', 'ANULADA'], true)
            ? $data['estado']
            : 'BORRADOR';

        $sql = "UPDATE facturas
                SET numero = :numero,
                    fecha = :fecha,
                    cliente_id = :cliente_id,
                    total = :total,
                    estado = :estado
                WHERE id = :id";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':numero', $data['numero'], PDO::PARAM_STR);
        $stmt->bindValue(':fecha', $data['fecha'], PDO::PARAM_STR);
        $stmt->bindValue(':cliente_id', (int) $data['cliente_id'], PDO::PARAM_INT);
        $stmt->bindValue(':total', (float) $data['total'], PDO::PARAM_STR);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM facturas WHERE id = :id";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}