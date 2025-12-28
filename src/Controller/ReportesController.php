<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\Controller;
use Erpia\Core\Auth;
use Erpia\Model\Factura;

class ReportesController extends Controller
{
    public function facturas(): void
    {
        Auth::can('facturas.ver');

        $desde = trim((string) ($_GET['desde'] ?? ''));
        $hasta = trim((string) ($_GET['hasta'] ?? ''));
        $estado = trim((string) ($_GET['estado'] ?? ''));

        if ($desde === '') {
            $desde = date('Y-m-01');
        }
        if ($hasta === '') {
            $hasta = date('Y-m-d');
        }

        $estadoParam = $estado !== '' ? $estado : null;

        $rows = [];
        $errors = [];

        try {
            $rows = Factura::reportePorFechas($desde, $hasta, $estadoParam);
        } catch (\Throwable $e) {
            $errors[] = 'No se pudo generar el reporte.';
        }

        $totalRegistros = count($rows);
        $sumaTotal = 0.0;
        $conteoPorEstado = [
            'BORRADOR' => 0,
            'EMITIDA' => 0,
            'PAGADA' => 0,
            'ANULADA' => 0,
        ];

        foreach ($rows as $r) {
            $sumaTotal += (float) ($r['total'] ?? 0);
            $est = (string) ($r['estado'] ?? '');
            if (isset($conteoPorEstado[$est])) {
                $conteoPorEstado[$est]++;
            }
        }

        $this->render('reportes/facturas', [
            'desde' => $desde,
            'hasta' => $hasta,
            'estado' => $estado,
            'rows' => $rows,
            'errors' => $errors,
            'kpis' => [
                'total_registros' => $totalRegistros,
                'suma_total' => $sumaTotal,
                'conteo_por_estado' => $conteoPorEstado,
            ],
        ]);
    }
}