<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Cliente
{
    protected static ?PDO $connection = null;

    protected static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dsn = 'mysql:host=localhost;dbname=erpia;charset=utf8mb4';
            $username = 'root';
            $password = '';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            self::$connection = new PDO($dsn, $username, $password, $options);
        }

        return self::$connection;
    }

    public static function getAll(): array
    {
        $sql = 'SELECT id, nombre, email, telefono, direccion, created_at 
                FROM clientes ORDER BY id DESC';
        $stmt = self::getConnection()->query($sql);

        return $stmt->fetchAll() ?: [];
    }

    public static function findById(int $id): ?array
    {
        $sql = 'SELECT id, nombre, email, telefono, direccion, created_at 
                FROM clientes WHERE id = :id LIMIT 1';
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $cliente = $stmt->fetch();

        return $cliente !== false ? $cliente : null;
    }

    public static function create(array $data): bool
    {
        $sql = 'INSERT INTO clientes (nombre, email, telefono, direccion, created_at)
                VALUES (:nombre, :email, :telefono, :direccion, :created_at)';

        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':nombre', $data['nombre'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(
            ':telefono',
            $data['telefono'] ?? null,
            $data['telefono'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':direccion',
            $data['direccion'] ?? null,
            $data['direccion'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function update(int $id, array $data): bool
    {
        $sql = 'UPDATE clientes
                SET nombre = :nombre,
                    email = :email,
                    telefono = :telefono,
                    direccion = :direccion
                WHERE id = :id';

        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':nombre', $data['nombre'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(
            ':telefono',
            $data['telefono'] ?? null,
            $data['telefono'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':direccion',
            $data['direccion'] ?? null,
            $data['direccion'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $sql = 'DELETE FROM clientes WHERE id = :id';
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}