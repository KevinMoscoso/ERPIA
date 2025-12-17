<?php

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Usuario
{
    public static function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email AND activo = 1 LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}