<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Rol
{
    public static function getAll(): array
    {
        $sql = "SELECT * FROM roles ORDER BY nombre ASC";
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
}