<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function __invoke(Request $request)
    {
        $datos = $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);
        $desde = $datos['desde'] ?? null;
        $hasta = $datos['hasta'] ?? null;

        // El rango de fechas se aplica a la fecha de la compra.
        $enRango = function ($join) use ($desde, $hasta) {
            $join->on('compras.oferta_id', '=', 'ofertas.id');
            if ($desde) {
                $join->whereDate('compras.created_at', '>=', $desde);
            }
            if ($hasta) {
                $join->whereDate('compras.created_at', '<=', $hasta);
            }
        };

        // Reporte 1: cupones vendidos, ventas y ganancias por empresa.
        $porEmpresa = DB::table('empresas')
            ->leftJoin('ofertas', 'ofertas.empresa_id', '=', 'empresas.id')
            ->leftJoin('compras', $enRango)
            ->where('empresas.estado', 'aprobada')
            ->groupBy('empresas.id', 'empresas.nombre', 'empresas.comision')
            ->orderByDesc('ventas')
            ->selectRaw('empresas.nombre, empresas.comision,
                COALESCE(SUM(compras.cantidad), 0) AS cupones,
                COALESCE(SUM(compras.total), 0) AS ventas')
            ->get()
            ->map(function ($fila) {
                $fila->ganancia_plataforma = round($fila->ventas * $fila->comision / 100, 2);
                $fila->ganancia_empresa = round($fila->ventas - $fila->ganancia_plataforma, 2);

                return $fila;
            });

        // Reporte 2: ventas por oferta.
        $porOferta = DB::table('ofertas')
            ->join('empresas', 'empresas.id', '=', 'ofertas.empresa_id')
            ->join('compras', $enRango)
            ->groupBy('ofertas.id', 'ofertas.titulo', 'empresas.nombre')
            ->orderByDesc('ventas')
            ->selectRaw('ofertas.titulo, empresas.nombre AS empresa,
                SUM(compras.cantidad) AS cupones, SUM(compras.total) AS ventas')
            ->limit(10)
            ->get();

        return view('admin.reportes', [
            'porEmpresa' => $porEmpresa,
            'porOferta' => $porOferta,
            'totalCupones' => (int) $porEmpresa->sum('cupones'),
            'totalVentas' => (float) $porEmpresa->sum('ventas'),
            'totalGanancia' => (float) $porEmpresa->sum('ganancia_plataforma'),
            'desde' => $desde,
            'hasta' => $hasta,
        ]);
    }
}
