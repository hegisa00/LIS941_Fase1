<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('cliente.dashboard', [
            'cliente' => $request->user()->cliente,
        ]);
    }
}
