<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $empresa = $request->user()->empresa;

        $resumen = $empresa->ofertas()
            ->join('compras', 'compras.oferta_id', '=', 'ofertas.id')
            ->selectRaw('COALESCE(SUM(compras.cantidad), 0) AS cupones, COALESCE(SUM(compras.total), 0) AS ventas')
            ->first();

        $ventas = (float) ($resumen->ventas ?? 0);

        return view('empresa.dashboard', [
            'empresa' => $empresa,
            'ofertasActivas' => $empresa->ofertas()->visibles()->count(),
            'cuponesVendidos' => (int) ($resumen->cupones ?? 0),
            'ventas' => $ventas,
            'neto' => round($ventas * (1 - (float) $empresa->comision / 100), 2),
        ]);
    }
}
