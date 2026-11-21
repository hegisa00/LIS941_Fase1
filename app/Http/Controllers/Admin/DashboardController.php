<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $porEstado = Empresa::selectRaw('estado, COUNT(*) AS total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $ventas = DB::table('compras')
            ->join('ofertas', 'ofertas.id', '=', 'compras.oferta_id')
            ->join('empresas', 'empresas.id', '=', 'ofertas.empresa_id')
            ->selectRaw('COALESCE(SUM(compras.cantidad), 0) AS cupones,
                COALESCE(SUM(compras.total), 0) AS ventas,
                COALESCE(SUM(compras.total * empresas.comision / 100), 0) AS ganancia')
            ->first();

        return view('admin.dashboard', [
            'cuponesVendidos' => (int) $ventas->cupones,
            'totalVentas' => (float) $ventas->ventas,
            'gananciaTotal' => (float) $ventas->ganancia,
            'pendientes' => (int) ($porEstado[Empresa::PENDIENTE] ?? 0),
            'aprobadas' => (int) ($porEstado[Empresa::APROBADA] ?? 0),
            'rechazadas' => (int) ($porEstado[Empresa::RECHAZADA] ?? 0),
            'clientes' => Cliente::count(),
            'administradores' => Administrador::count(),
        ]);
    }
}
