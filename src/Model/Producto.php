<?php

namespace Erpia\Model;

use Erpia\Core\Model;

class Producto extends Model
{
    public static function getAll(): array
    {
        $db = self::db();
        $stmt = $db->query("SELECT * FROM productos ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function create(array $data): bool
    {
        $db = self::db();
        $stmt = $db->prepare(
            "INSERT INTO productos (nombre, descripcion, precio, stock, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );

        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $db = self::db();
        $stmt = $db->prepare(
            "UPDATE productos
             SET nombre = ?, descripcion = ?, precio = ?, stock = ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}