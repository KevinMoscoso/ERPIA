<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Database;
use Erpia\Model\Pago;
use Erpia\Model\Factura;

class PagosController
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
        $saldo = (float) ($factura['total'] ?? 0) - $totalPagado;

        View::render('pagos/index', [
            'factura' => $factura,
            'pagos' => $pagos,
            'totalPagado' => $totalPagado,
            'saldo' => $saldo,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function crear($facturaId): void
    {
        $facturaId = (int) $facturaId;

        $factura = Factura::findById($facturaId);
        if ($factura === null) {
            throw new \RuntimeException('Factura no encontrada.');
        }

        if (($factura['estado'] ?? '') !== 'EMITIDA') {
            header('Location: /pagos/index/' . $facturaId . '?error=estado');
            exit;
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

        if (($factura['estado'] ?? '') !== 'EMITIDA') {
            header('Location: /pagos/index/' . $facturaId . '?error=estado');
            exit;
        }

        $monto  = (float) ($_POST['monto'] ?? 0);
        $metodo = trim((string) ($_POST['metodo'] ?? ''));
        $fecha  = (string) ($_POST['fecha'] ?? '');

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

        Factura::actualizarEstadoSegunPagos($facturaId);

        header('Location: /pagos/index/' . $facturaId);
        exit;
    }

    public function eliminar($id): void
    {
        $id = (int) $id;

        $stmt = Database::getConnection()->prepare("SELECT factura_id FROM pagos WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $facturaId = (int) ($stmt->fetchColumn() ?? 0);

        if ($facturaId <= 0) {
            header('Location: /facturas');
            exit;
        }

        $factura = Factura::findById($facturaId);
        if ($factura === null) {
            header('Location: /facturas');
            exit;
        }

        if (($factura['estado'] ?? '') !== 'EMITIDA') {
            header('Location: /pagos/index/' . $facturaId . '?error=estado');
            exit;
        }

        Pago::delete($id);

        Factura::actualizarEstadoSegunPagos($facturaId);

        header('Location: /pagos/index/' . $facturaId);
        exit;
    }

    public function factura(int $facturaId): void
    {
        $this->index($facturaId);
    }
}