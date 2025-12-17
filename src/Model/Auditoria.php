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
}