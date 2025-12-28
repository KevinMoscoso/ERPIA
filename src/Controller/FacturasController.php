<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Core\Database;
use Erpia\Core\Auth;
use Erpia\Model\Auditoria;
use Erpia\Model\Factura;
use Erpia\Model\FacturaDetalle;
use Erpia\Model\InventarioMovimiento;

class FacturasController extends Controller
{
    private function userId(): int
    {
        $u = Auth::user();
        return (int) ($u['id'] ?? 0);
    }

    /* =========================
     * LISTADO
     * ========================= */
    public function index(): void
    {
        Auth::can('facturas.ver');

        $q = trim((string) ($_GET['q'] ?? ''));

        $facturas = $q !== ''
            ? Factura::searchByNumero($q)
            : Factura::getAll();

        View::render('facturas/index', [
            'facturas' => $facturas,
            'q' => $q,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    /* =========================
     * CREAR FACTURA
     * ========================= */
    public function crear(): void
    {
        Auth::can('facturas.crear');

        View::render('facturas/crear', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function guardar(): void
    {
        Auth::can('facturas.crear');

        $data = [
            'numero'     => trim((string) ($_POST['numero'] ?? '')),
            'fecha'      => (string) ($_POST['fecha'] ?? ''),
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'estado'     => (string) ($_POST['estado'] ?? 'BORRADOR'),
        ];

        if ($data['numero'] === '' || $data['fecha'] === '' || $data['cliente_id'] <= 0) {
            View::render('facturas/crear', [
                'errors' => ['form' => 'Datos inválidos'],
                'old' => $data,
            ]);
            return;
        }

        $db = Database::getConnection();
        Factura::create($data);

        // Auditoría (si lastInsertId no aplica por conexión distinta, igual no rompe)
        $facturaId = (int) $db->lastInsertId();
        if ($facturaId > 0) {
            Auditoria::registrar($this->userId(), 'factura.crear', 'factura:' . $facturaId);
        }

        header('Location: /facturas');
        exit;
    }

    /* =========================
     * EDITAR FACTURA
     * ========================= */
    public function editar(int $id): void
    {
        Auth::can('facturas.editar');
        Factura::assertPuedeEditar($id);

        $factura = Factura::findById($id);
        if (!$factura) {
            throw new \RuntimeException('Factura no encontrada');
        }

        View::render('facturas/editar', [
            'factura' => $factura,
            'errors' => [],
        ]);
    }

    public function actualizar(int $id): void
    {
        Auth::can('facturas.editar');
        Factura::assertPuedeEditar($id);

        $data = [
            'numero'     => trim((string) ($_POST['numero'] ?? '')),
            'fecha'      => (string) ($_POST['fecha'] ?? ''),
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'estado'     => (string) ($_POST['estado'] ?? 'BORRADOR'),
        ];

        Factura::update($id, $data);

        Auditoria::registrar($this->userId(), 'factura.actualizar', 'factura:' . $id);

        header('Location: /facturas');
        exit;
    }

    /* =========================
     * ELIMINAR FACTURA
     * ========================= */
    public function eliminar(int $id): void
    {
        Auth::can('facturas.eliminar');

        Factura::delete($id);

        Auditoria::registrar($this->userId(), 'factura.eliminar', 'factura:' . $id);

        header('Location: /facturas');
        exit;
    }

    /* =========================
     * EMITIR / ANULAR (Flujo)
     * ========================= */
    public function emitir(int $id): void
    {
        Auth::can('facturas.emitir');

        try {
            Factura::emitir($id);
            Auditoria::registrar($this->userId(), 'factura.emitir', 'factura:' . $id);

            header('Location: /facturas');
            exit;
        } catch (\Throwable $e) {
            header('Location: /facturas?error=emitir');
            exit;
        }
    }

    public function anular(int $id): void
    {
        Auth::can('facturas.anular');

        try {
            Factura::anular($id);
            Auditoria::registrar($this->userId(), 'factura.anular', 'factura:' . $id);

            header('Location: /facturas');
            exit;
        } catch (\Throwable $e) {
            header('Location: /facturas?error=anular');
            exit;
        }
    }

    /* =========================
     * DETALLE DE FACTURA
     * ========================= */
    public function detalle(int $facturaId): void
    {
        Auth::can('facturas.detalle');

        $factura = Factura::findById($facturaId);
        if (!$factura) {
            throw new \RuntimeException('Factura no encontrada');
        }

        $detalles = FacturaDetalle::getByFactura($facturaId);

        View::render('facturas/detalle', [
            'factura' => $factura,
            'detalles' => $detalles,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    /* =========================
     * MODIFICAR DETALLES
     * ========================= */
    public function agregarDetalle(int $facturaId): void
    {
        Auth::can('facturas.detalle.modificar');
        Factura::assertPuedeModificarDetalles($facturaId);

        $data = [
            'factura_id'      => $facturaId,
            'producto_id'     => (int) ($_POST['producto_id'] ?? 0),
            'cantidad'        => (int) ($_POST['cantidad'] ?? 0),
            'precio_unitario' => (float) ($_POST['precio_unitario'] ?? 0),
        ];

        if ($data['producto_id'] <= 0 || $data['cantidad'] <= 0 || $data['precio_unitario'] <= 0) {
            header("Location: /facturas/detalle/$facturaId");
            exit;
        }

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Lock de stock para evitar carreras
            $stmtStock = $db->prepare("SELECT stock FROM productos WHERE id = :id FOR UPDATE");
            $stmtStock->bindValue(':id', $data['producto_id'], \PDO::PARAM_INT);
            $stmtStock->execute();
            $stockActual = (int) ($stmtStock->fetchColumn() ?? 0);

            if ($stockActual - $data['cantidad'] < 0) {
                $db->rollBack();
                header("Location: /facturas/detalle/$facturaId?error=stock");
                exit;
            }

            // 1) detalle
            FacturaDetalle::create($data);

            // 2) inventario salida
            $factura = Factura::findById($facturaId);
            $ref = $factura && trim((string) ($factura['numero'] ?? '')) !== ''
                ? (string) $factura['numero']
                : (string) $facturaId;

            InventarioMovimiento::registrarSalidaFactura(
                $data['producto_id'],
                $data['cantidad'],
                $facturaId,
                'Salida por factura ' . $ref
            );

            // 3) total
            Factura::recalcularTotal($facturaId);

            Auditoria::registrar(
                $this->userId(),
                'factura.detalle.agregar',
                'factura:' . $facturaId . '|producto:' . $data['producto_id']
            );

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        header("Location: /facturas/detalle/$facturaId");
        exit;
    }

    public function eliminarDetalle(int $detalleId): void
    {
        $detalle = FacturaDetalle::findById($detalleId);
        if (!$detalle) {
            throw new \RuntimeException('Detalle no encontrado');
        }

        $facturaId = (int) ($detalle['factura_id'] ?? 0);

        Auth::can('facturas.detalle.modificar');
        Factura::assertPuedeModificarDetalles($facturaId);

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            FacturaDetalle::delete($detalleId);

            InventarioMovimiento::registrarEntradaFactura(
                (int) $detalle['producto_id'],
                (int) $detalle['cantidad'],
                $facturaId,
                'Reverso por eliminación detalle factura ' . $facturaId
            );

            Factura::recalcularTotal($facturaId);

            Auditoria::registrar(
                $this->userId(),
                'factura.detalle.eliminar',
                'factura:' . $facturaId . '|detalle:' . $detalleId
            );

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        header("Location: /facturas/detalle/" . $facturaId);
        exit;
    }
}