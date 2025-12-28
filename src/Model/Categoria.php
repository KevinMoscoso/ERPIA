<?php

namespace Erpia\Model;

use Erpia\Core\Model;

class Categoria extends Model
{
    public static function getAll(): array
    {
        $db = self::db();
        return $db->query("SELECT * FROM categorias ORDER BY id DESC")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): bool
    {
        $db = self::db();
        $stmt = $db->prepare(
            "INSERT INTO categorias (nombre, descripcion, created_at)
             VALUES (?, ?, NOW())"
        );
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $db = self::db();
        $stmt = $db->prepare(
            "UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }
}