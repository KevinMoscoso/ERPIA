<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Database;
use PDO;
use RuntimeException;

class InventarioMovimiento
{
    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    /* =========================================================
     * MÉTODO ÚNICO PARA MODIFICAR STOCK (INVARIANTE CENTRAL)
     * ========================================================= */
    public static function ajustarStockSeguro(int $productoId, int $delta): void
    {
        $db = self::db();

        // 1. Bloqueo de concurrencia
        $stmt = $db->prepare(
            "SELECT stock FROM productos WHERE id = :id FOR UPDATE"
        );
        $stmt->execute([':id' => $productoId]);

        $stockActual = $stmt->fetchColumn();
        if ($stockActual === false) {
            throw new RuntimeException('Producto no encontrado');
        }

        $stockActual = (int) $stockActual;
        $nuevoStock  = $stockActual + $delta;

        // 2. Invariante: stock nunca negativo
        if ($nuevoStock < 0) {
            throw new RuntimeException('Stock insuficiente');
        }

        // 3. Actualizar stock
        $stmt = $db->prepare(
            "UPDATE productos SET stock = :stock WHERE id = :id"
        );
        $stmt->execute([
            ':stock' => $nuevoStock,
            ':id'    => $productoId,
        ]);
    }

    /* =========================================================
     * REGISTRO DE MOVIMIENTOS (NO MODIFICA STOCK)
     * ========================================================= */
    public static function registrarMovimiento(array $data): void
    {
        $sql = "INSERT INTO inventario_movimientos
                (producto_id, tipo, cantidad, referencia_tipo, referencia_id, observacion, created_at)
                VALUES
                (:producto_id, :tipo, :cantidad, :referencia_tipo, :referencia_id, :observacion, NOW())";

        $stmt = self::db()->prepare($sql);

        $stmt->bindValue(':producto_id', (int) $data['producto_id'], PDO::PARAM_INT);
        $stmt->bindValue(':tipo', (string) $data['tipo'], PDO::PARAM_STR);
        $stmt->bindValue(':cantidad', (int) $data['cantidad'], PDO::PARAM_INT);
        $stmt->bindValue(':referencia_tipo', (string) $data['referencia_tipo'], PDO::PARAM_STR);

        // referencia_id nullable
        if ($data['referencia_id'] === null) {
            $stmt->bindValue(':referencia_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':referencia_id', (int) $data['referencia_id'], PDO::PARAM_INT);
        }

        // observacion nullable
        $obs = $data['observacion'] ?? null;
        if ($obs === null || $obs === '') {
            $stmt->bindValue(':observacion', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':observacion', (string) $obs, PDO::PARAM_STR);
        }

        $stmt->execute();
    }

    /* =========================================================
     * FACTURACIÓN (USA EL MÉTODO SEGURO)
     * ========================================================= */
    public static function registrarSalidaFactura(
        int $productoId,
        int $cantidad,
        int $facturaId,
        ?string $obs = null
    ): void {
        // 1. Ajustar stock (valida y bloquea)
        self::ajustarStockSeguro($productoId, -$cantidad);

        // 2. Registrar movimiento
        self::registrarMovimiento([
            'producto_id'     => $productoId,
            'tipo'            => 'SALIDA',
            'cantidad'        => $cantidad,
            'referencia_tipo' => 'FACTURA',
            'referencia_id'   => $facturaId,
            'observacion'     => $obs,
        ]);
    }

    public static function registrarEntradaFactura(
        int $productoId,
        int $cantidad,
        int $facturaId,
        ?string $obs = null
    ): void {
        // 1. Ajustar stock
        self::ajustarStockSeguro($productoId, $cantidad);

        // 2. Registrar movimiento
        self::registrarMovimiento([
            'producto_id'     => $productoId,
            'tipo'            => 'ENTRADA',
            'cantidad'        => $cantidad,
            'referencia_tipo' => 'FACTURA',
            'referencia_id'   => $facturaId,
            'observacion'     => $obs,
        ]);
    }

    /* =========================================================
     * CONSULTAS
     * ========================================================= */
    public static function getByProducto(int $productoId): array
    {
        $sql = "SELECT *
                FROM inventario_movimientos
                WHERE producto_id = :id
                ORDER BY id DESC";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $productoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}