<?php

declare(strict_types=1);

namespace Erpia\Controller;

use Erpia\Core\View;
use Erpia\Core\Auth;
use Erpia\Model\Factura;
use Erpia\Model\Producto;
use Erpia\Model\Compra;

class DashboardController
{
    public function index(): void
    {
        Auth::check(); // 🔐 protege el dashboard

        $user = Auth::user();

        // Helper local para permisos (compatible con has() o can())
        $has = static function (string $perm): bool {
            if (method_exists(\Erpia\Core\Auth::class, 'has')) {
                return (bool) \Erpia\Core\Auth::has($perm);
            }
            return (bool) \Erpia\Core\Auth::can($perm, false);
        };

        // Valores por defecto (dashboard siempre estable)
        $kpis = [
            'facturacion_hoy' => 0.0,
            'facturacion_mes' => 0.0,
            'facturas_pendientes' => 0,
            'stock_bajo' => [],
            'compras_recientes' => [],
        ];

        // KPIs de Facturas
        if ($has('facturas.ver')) {
            try {
                $kpis['facturacion_hoy'] = Factura::sumHoy();
                $kpis['facturacion_mes'] = Factura::sumMesActual();
                $kpis['facturas_pendientes'] = Factura::countPendientes();
            } catch (\Throwable $e) {
                // mantener valores por defecto
            }
        }

        // KPI de Inventario
        if ($has('inventario.ver')) {
            try {
                $kpis['stock_bajo'] = Producto::getStockBajo(5, 5);
            } catch (\Throwable $e) {
                // mantener valores por defecto
            }
        }

        // KPI de Compras
        if ($has('compras.ver')) {
            try {
                $kpis['compras_recientes'] = Compra::getRecientes(5);
            } catch (\Throwable $e) {
                // mantener valores por defecto
            }
        }

        View::render('dashboard/index', [
            'user' => $user,
            'kpis' => $kpis,
        ]);
    }
}