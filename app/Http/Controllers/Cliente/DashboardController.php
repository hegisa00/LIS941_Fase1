<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $cliente = $request->user()->cliente;

        return view('cliente.dashboard', [
            'cliente' => $cliente,
            'totalCompras' => $cliente->compras()->count(),
            'totalCupones' => (int) $cliente->compras()->sum('cantidad'),
        ]);
    }
}
