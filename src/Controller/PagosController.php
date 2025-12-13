<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\View;
use Erpia\Model\Pago;
use Erpia\Model\Factura;
use Erpia\Core\Database;

class PagosController extends Controller
{
    public function index($facturaId): void
    {
        $facturaId = (int) $facturaId;
        $factura = Factura::findById($facturaId);

        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        $pagos = Pago::getByFacturaId($facturaId);
        $totalPagado = Pago::getTotalPagado($facturaId);
        $saldo = (float) $factura['total'] - $totalPagado;

        View::render('pagos/index', [
            'factura' => $factura,
            'pagos' => $pagos,
            'totalPagado' => $totalPagado,
            'saldo' => $saldo,
        ]);
    }

    public function crear($facturaId): void
    {
        $factura = Factura::findById((int) $facturaId);

        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        View::render('pagos/crear', [
            'factura' => $factura,
            'errors' => [],
        ]);
    }

    public function guardar($facturaId): void
    {
        $facturaId = (int) $facturaId;
        $factura = Factura::findById($facturaId);

        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        $monto = (float) ($_POST['monto'] ?? 0);
        $metodo = trim($_POST['metodo'] ?? '');
        $fecha = $_POST['fecha'] ?? '';

        if ($monto <= 0 || $metodo === '' || $fecha === '') {
            View::render('pagos/crear', [
                'factura' => $factura,
                'errors' => ['form' => 'Datos inválidos'],
            ]);
            return;
        }

        Pago::create([
            'factura_id' => $facturaId,
            'monto' => $monto,
            'metodo' => $metodo,
            'fecha' => $fecha,
        ]);

        $totalPagado = Pago::getTotalPagado($facturaId);
        $saldo = (float) $factura['total'] - $totalPagado;

        if ($saldo <= 0) {
            $sql = "UPDATE facturas SET estado = 'PAGADA' WHERE id = :id";
            $stmt = Database::getConnection()->prepare($sql);
            $stmt->bindValue(':id', $facturaId, \PDO::PARAM_INT);
            $stmt->execute();
        }

        header('Location: /pagos/index/' . $facturaId);
        exit;
    }

    public function eliminar($id): void
    {
        $id = (int) $id;

        $sql = "SELECT factura_id FROM pagos WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $facturaId = (int) $stmt->fetchColumn();

        Pago::delete($id);

        header('Location: /pagos/index/' . $facturaId);
        exit;
    }
}