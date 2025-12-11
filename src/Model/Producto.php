<?php

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Producto
{
    public static function getAll(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM productos ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function create(array $data): bool
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, created_at)
                VALUES (:nombre, :descripcion, :precio, :stock, NOW())";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':stock' => $data['stock'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getConnection();
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion,
                precio = :precio, stock = :stock WHERE id = :id";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':stock' => $data['stock'],
            ':id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
