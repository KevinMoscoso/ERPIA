<?php

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;

class Permiso
{
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
}