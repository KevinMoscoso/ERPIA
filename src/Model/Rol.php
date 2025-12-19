<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Rol
{
    public static function getAll(): array
    {
        $sql = "SELECT * FROM roles ORDER BY id ASC";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM roles WHERE id = :id LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** 🔽 MÉTODO FALTANTE */
    public static function create(array $data): ?int
    {
        $nombre = strtoupper(trim((string)($data['nombre'] ?? '')));
        if ($nombre === '') {
            return null;
        }

        $sql = "INSERT INTO roles (nombre) VALUES (:nombre)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            return null;
        }

        return (int) Database::getConnection()->lastInsertId();
    }

    /** 🔽 MÉTODO FALTANTE */
    public static function update(int $id, array $data): bool
    {
        $nombre = strtoupper(trim((string)($data['nombre'] ?? '')));
        if ($id <= 0 || $nombre === '') {
            return false;
        }

        $sql = "UPDATE roles SET nombre = :nombre WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);

        return $stmt->execute();
    }
}