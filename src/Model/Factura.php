<?php

declare(strict_types=1);

namespace Erpia\Model;

use Erpia\Core\Model;
use Erpia\Core\Database;
use PDO;

class Factura extends Model
{
    private const ESTADOS_VALIDOS = ['BORRADOR', 'EMITIDA', 'PAGADA', 'ANULADA'];

    public static function getAll(): array
    {
        $sql = "SELECT f.*, c.nombre AS cliente_nombre
                FROM facturas f
                LEFT JOIN clientes c ON c.id = f.cliente_id
                ORDER BY f.id DESC";

        $stmt = self::db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function searchByNumero(string $q): array
    {
        $sql = "SELECT f.*, c.nombre AS cliente_nombre
                FROM facturas f
                LEFT JOIN clientes c ON c.id = f.cliente_id
                WHERE f.numero LIKE :q
                ORDER BY f.id DESC";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':q', '%' . $q . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM facturas WHERE id = :id LIMIT 1";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    public static function create(array $data): bool
    {
        $numero = trim((string) ($data['numero'] ?? ''));
        $fecha = (string) ($data['fecha'] ?? '');
        $clienteId = (int) ($data['cliente_id'] ?? 0);

        if ($numero === '' || $fecha === '' || $clienteId <= 0) {
            return false;
        }

        $estado = self::normalizarEstado((string) ($data['estado'] ?? 'BORRADOR'));

        // total siempre arranca en 0 y se recalcula con detalles
        $sql = "INSERT INTO facturas (numero, fecha, cliente_id, total, estado, created_at)
                VALUES (:numero, :fecha, :cliente_id, 0.00, :estado, NOW())";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':numero', $numero, PDO::PARAM_STR);
        $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function update(int $id, array $data): bool
    {
        $numero = trim((string) ($data['numero'] ?? ''));
        $fecha = (string) ($data['fecha'] ?? '');
        $clienteId = (int) ($data['cliente_id'] ?? 0);

        if ($numero === '' || $fecha === '' || $clienteId <= 0) {
            return false;
        }

        $estado = self::normalizarEstado((string) ($data['estado'] ?? 'BORRADOR'));

        $sql = "UPDATE facturas
                SET numero = :numero,
                    fecha = :fecha,
                    cliente_id = :cliente_id,
                    estado = :estado
                WHERE id = :id";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':numero', $numero, PDO::PARAM_STR);
        $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM facturas WHERE id = :id";
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function recalcularTotal(int $facturaId): void
    {
        $sql = "UPDATE facturas
                SET total = (
                    SELECT IFNULL(SUM(subtotal), 0)
                    FROM factura_detalles
                    WHERE factura_id = :fid
                )
                WHERE id = :fid2";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':fid', $facturaId, PDO::PARAM_INT);
        $stmt->bindValue(':fid2', $facturaId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function assertPuedeEditar(int $facturaId): void
    {
        $factura = self::findById($facturaId);
        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }
        if (($factura['estado'] ?? '') !== 'BORRADOR') {
            throw new \RuntimeException('La factura no se puede editar en este estado.');
        }
    }

    public static function assertPuedeModificarDetalles(int $facturaId): void
    {
        $factura = self::findById($facturaId);
        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }
        if (($factura['estado'] ?? '') !== 'BORRADOR') {
            throw new \RuntimeException('No se pueden modificar detalles en este estado.');
        }
    }

    public static function emitir(int $facturaId): void
    {
        $factura = self::findById($facturaId);
        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }
        if (($factura['estado'] ?? '') !== 'BORRADOR') {
            throw new \RuntimeException('Solo se puede emitir una factura en BORRADOR.');
        }

        $stmt = self::db()->prepare("UPDATE facturas SET estado = 'EMITIDA' WHERE id = :id");
        $stmt->bindValue(':id', $facturaId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function anular(int $facturaId): void
    {
        $db = Database::getConnection();

        $factura = self::findById($facturaId);
        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        $estado = (string) ($factura['estado'] ?? '');
        if (!in_array($estado, ['BORRADOR', 'EMITIDA'], true)) {
            throw new \RuntimeException('No se puede anular una factura en este estado.');
        }

        // Bloquear si hay pagos
        $totalPagado = Pago::getTotalPagado($facturaId);
        if ($totalPagado > 0) {
            throw new \RuntimeException('No se puede anular: la factura tiene pagos registrados.');
        }

        try {
            $db->beginTransaction();

            // Revertir stock de todos los detalles
            $sqlDetalles = "SELECT producto_id, cantidad
                            FROM factura_detalles
                            WHERE factura_id = :fid";
            $stmtDet = $db->prepare($sqlDetalles);
            $stmtDet->bindValue(':fid', $facturaId, PDO::PARAM_INT);
            $stmtDet->execute();
            $detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC) ?: [];

            $numero = trim((string) ($factura['numero'] ?? ''));
            $ref = $numero !== '' ? $numero : ('#' . $facturaId);

            foreach ($detalles as $d) {
                $productoId = (int) ($d['producto_id'] ?? 0);
                $cantidad = (int) ($d['cantidad'] ?? 0);
                if ($productoId > 0 && $cantidad > 0) {
                    // Usa tu helper estándar para que el movimiento y el stock queden consistentes
                    InventarioMovimiento::registrarEntradaFactura(
                        $productoId,
                        $cantidad,
                        $facturaId,
                        'Anulación factura ' . $ref
                    );
                }
            }

            $stmtAn = $db->prepare("UPDATE facturas SET estado = 'ANULADA' WHERE id = :id");
            $stmtAn->bindValue(':id', $facturaId, PDO::PARAM_INT);
            $stmtAn->execute();

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function actualizarEstadoSegunPagos(int $facturaId): void
    {
        $factura = self::findById($facturaId);
        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        $estadoActual = (string) ($factura['estado'] ?? '');
        if ($estadoActual === 'ANULADA' || $estadoActual === 'BORRADOR') {
            return;
        }

        $total = (float) ($factura['total'] ?? 0);
        $totalPagado = Pago::getTotalPagado($facturaId);

        if ($total > 0 && $totalPagado >= $total) {
            if ($estadoActual !== 'PAGADA') {
                $stmt = self::db()->prepare("UPDATE facturas SET estado = 'PAGADA' WHERE id = :id");
                $stmt->bindValue(':id', $facturaId, PDO::PARAM_INT);
                $stmt->execute();
            }
            return;
        }

        if ($estadoActual === 'PAGADA') {
            $stmt = self::db()->prepare("UPDATE facturas SET estado = 'EMITIDA' WHERE id = :id");
            $stmt->bindValue(':id', $facturaId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    private static function normalizarEstado(string $estado): string
    {
        $estado = strtoupper(trim($estado));
        return in_array($estado, self::ESTADOS_VALIDOS, true) ? $estado : 'BORRADOR';
    }
}