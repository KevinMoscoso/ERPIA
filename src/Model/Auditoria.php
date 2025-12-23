<?php

namespace Erpia\Model;

use Erpia\Core\Database;

class Auditoria
{
    public static function registrar(int $usuarioId, string $accion, ?string $ref = null): void
    {
        $sql = "INSERT INTO auditoria (usuario_id, accion, referencia)
                VALUES (:usuario_id, :accion, :referencia)";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'referencia' => $ref,
        ]);
    }

    public static function getByFiltros(?int $usuarioId, string $desde, string $hasta): array
    {
        $d1 = \DateTime::createFromFormat('Y-m-d', $desde);
        $d2 = \DateTime::createFromFormat('Y-m-d', $hasta);

        if (!$d1 || $d1->format('Y-m-d') !== $desde) {
            return [];
        }
        if (!$d2 || $d2->format('Y-m-d') !== $hasta) {
            return [];
        }

        $desdeDt = $desde . ' 00:00:00';
        $hastaDt = $hasta . ' 23:59:59';

        $db = \Erpia\Core\Database::getConnection();

        $sql = "
            SELECT a.*, u.nombre AS usuario_nombre
            FROM auditoria a
            JOIN usuarios u ON u.id = a.usuario_id
            WHERE a.created_at >= :desde AND a.created_at <= :hasta
        ";

        $params = [
            ':desde' => $desdeDt,
            ':hasta' => $hastaDt,
        ];

        if ($usuarioId !== null && $usuarioId > 0) {
            $sql .= " AND a.usuario_id = :usuario_id";
            $params[':usuario_id'] = $usuarioId;
        }

        $sql .= " ORDER BY a.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}