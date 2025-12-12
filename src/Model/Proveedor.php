<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use Erpia\Core\Model;
use PDO;

class Proveedor extends Model
{
    protected static function db(): PDO
    {
        if (method_exists(Database::class, 'getConnection')) {
            /** @var PDO $pdo */
            $pdo = Database::getConnection();
            return $pdo;
        }

        if (method_exists(Database::class, 'connection')) {
            /** @var PDO $pdo */
            $pdo = Database::connection();
            return $pdo;
        }

        throw new \RuntimeException('Database connection is not configured.');
    }

    public static function getAll(): array
    {
        $sql = 'SELECT id, nombre, email, telefono, direccion, created_at
                FROM proveedores
                ORDER BY id DESC';

        $stmt = self::db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(int $id): ?array
    {
        $sql = 'SELECT id, nombre, email, telefono, direccion, created_at
                FROM proveedores
                WHERE id = :id
                LIMIT 1';

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    public static function create(array $data): bool
    {
        $sql = 'INSERT INTO proveedores (nombre, email, telefono, direccion, created_at)
                VALUES (:nombre, :email, :telefono, :direccion, :created_at)';

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':nombre', (string) ($data['nombre'] ?? ''), PDO::PARAM_STR);

        $email = $data['email'] ?? null;
        $telefono = $data['telefono'] ?? null;
        $direccion = $data['direccion'] ?? null;

        $stmt->bindValue(':email', $email, $email === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $telefono, $telefono === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':direccion', $direccion, $direccion === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function update(int $id, array $data): bool
    {
        $sql = 'UPDATE proveedores
                SET nombre = :nombre,
                    email = :email,
                    telefono = :telefono,
                    direccion = :direccion
                WHERE id = :id';

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', (string) ($data['nombre'] ?? ''), PDO::PARAM_STR);

        $email = $data['email'] ?? null;
        $telefono = $data['telefono'] ?? null;
        $direccion = $data['direccion'] ?? null;

        $stmt->bindValue(':email', $email, $email === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $telefono, $telefono === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':direccion', $direccion, $direccion === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $sql = 'DELETE FROM proveedores WHERE id = :id';

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}