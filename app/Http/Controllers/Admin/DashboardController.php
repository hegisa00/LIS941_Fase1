<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Empresa;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $porEstado = Empresa::selectRaw('estado, COUNT(*) AS total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return view('admin.dashboard', [
            'pendientes' => (int) ($porEstado[Empresa::PENDIENTE] ?? 0),
            'aprobadas' => (int) ($porEstado[Empresa::APROBADA] ?? 0),
            'rechazadas' => (int) ($porEstado[Empresa::RECHAZADA] ?? 0),
            'clientes' => Cliente::count(),
            'administradores' => Administrador::count(),
        ]);
    }
}
