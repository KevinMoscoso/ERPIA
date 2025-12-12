<?php

namespace Erpia\Model;

use Erpia\Core\Model;

class Cliente extends Model
{
    public static function getAll(): array
    {
        $db = self::db();
        return $db->query("SELECT * FROM clientes ORDER BY id DESC")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): bool
    {
        $db = self::db();
        $stmt = $db->prepare(
            "INSERT INTO clientes (nombre, email, telefono, direccion, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['direccion'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $db = self::db();
        $stmt = $db->prepare(
            "UPDATE clientes
             SET nombre = ?, email = ?, telefono = ?, direccion = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['direccion'],
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM clientes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}