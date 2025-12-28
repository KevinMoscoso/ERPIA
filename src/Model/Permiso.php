<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Permiso
{
    /**
     * USADO POR AUTH / RBAC (NO TOCAR COMPORTAMIENTO)
     * Devuelve las claves de permisos del rol
     */
    public static function getPermisosByRol(int $rolId): array
    {
        $sql = "SELECT p.clave
                FROM permisos p
                INNER JOIN rol_permiso rp ON rp.permiso_id = p.id
                WHERE rp.rol_id = :rol_id";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * USADO POR LA UI DE ROLES
     * Lista todos los permisos del sistema
     */
    public static function getAll(): array
    {
        $sql = "SELECT id, clave, descripcion
                FROM permisos
                ORDER BY clave ASC";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * USADO POR LA UI DE ROLES
     * Devuelve IDs de permisos asignados al rol
     */
    public static function getByRol(int $rolId): array
    {
        $sql = "SELECT permiso_id
                FROM rol_permiso
                WHERE rol_id = :rol_id";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    /**
     * USADO POR LA UI DE ROLES
     * Sincroniza permisos de un rol (borra e inserta)
     */
    public static function syncRolPermisos(int $rolId, array $permisos): void
    {
        $db = Database::getConnection();

        $db->beginTransaction();
        try {
            $stmtDelete = $db->prepare(
                "DELETE FROM rol_permiso WHERE rol_id = :rol_id"
            );
            $stmtDelete->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
            $stmtDelete->execute();

            if (!empty($permisos)) {
                $stmtInsert = $db->prepare(
                    "INSERT INTO rol_permiso (rol_id, permiso_id)
                     VALUES (:rol_id, :permiso_id)"
                );

                foreach ($permisos as $permisoId) {
                    $stmtInsert->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
                    $stmtInsert->bindValue(':permiso_id', (int)$permisoId, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
}