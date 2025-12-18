<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Usuario
{
    /* =========================
     *  MÉTODOS EXISTENTES (NO SE ROMPEN)
     * ========================= */

    public static function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email AND activo = 1 LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
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

    /* =========================
     *  NUEVOS MÉTODOS (FASE 5.3)
     * ========================= */

    public static function getAll(?string $q = null): array
    {
        $sql = "SELECT u.*, r.nombre AS rol_nombre
                FROM usuarios u
                LEFT JOIN roles r ON r.id = u.rol_id";

        $params = [];

        if ($q !== null && trim($q) !== '') {
            $sql .= " WHERE (u.nombre LIKE :q_nombre OR u.email LIKE :q_email)";
            $params[':q_nombre'] = '%' . trim($q) . '%';
            $params[':q_email']  = '%' . trim($q) . '%';
        }

        $sql .= " ORDER BY u.id DESC";

        $stmt = Database::getConnection()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function emailExists(string $email, ?int $ignoreId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        if ($ignoreId !== null) {
            $sql .= " AND id <> :ignore_id";
        }

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        if ($ignoreId !== null) {
            $stmt->bindValue(':ignore_id', $ignoreId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }

    public static function create(array $data): bool
    {
        $nombre = trim((string)($data['nombre'] ?? ''));
        $email  = strtolower(trim((string)($data['email'] ?? '')));
        $password = (string)($data['password'] ?? '');
        $rolId  = (int)($data['rol_id'] ?? 0);
        $activo = (int)($data['activo'] ?? 1);

        if ($nombre === '' || $email === '' || $rolId <= 0 || mb_strlen($password) < 8) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        if ($hash === false) {
            return false;
        }

        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id, activo, created_at)
                VALUES (:nombre, :email, :password, :rol_id, :activo, NOW())";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
        $stmt->bindValue(':activo', $activo ? 1 : 0, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function update(int $id, array $data): bool
    {
        $nombre = trim((string)($data['nombre'] ?? ''));
        $email  = strtolower(trim((string)($data['email'] ?? '')));
        $rolId  = (int)($data['rol_id'] ?? 0);
        $activo = (int)($data['activo'] ?? 1);

        if ($id <= 0 || $nombre === '' || $email === '' || $rolId <= 0) {
            return false;
        }

        $sql = "UPDATE usuarios
                SET nombre = :nombre,
                    email = :email,
                    rol_id = :rol_id,
                    activo = :activo
                WHERE id = :id";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
        $stmt->bindValue(':activo', $activo ? 1 : 0, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function updatePassword(int $id, string $plainPassword): bool
    {
        if ($id <= 0 || mb_strlen($plainPassword) < 8) {
            return false;
        }

        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
        if ($hash === false) {
            return false;
        }

        $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', $hash, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function toggleActivo(int $id): bool
    {
        $usuario = self::findById($id);
        if ($usuario === null) {
            return false;
        }

        $nuevoEstado = ((int)$usuario['activo'] === 1) ? 0 : 1;

        $sql = "UPDATE usuarios SET activo = :activo WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':activo', $nuevoEstado, PDO::PARAM_INT);

        return $stmt->execute();
    }
}