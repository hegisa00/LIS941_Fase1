<?php

namespace App\Http\Controllers;

use App\Models\Oferta;

class HomeController extends Controller
{
    public function index()
    {
        $ofertas = Oferta::with('empresa')
            ->visibles()
            ->latest()
            ->paginate(9);

        return view('home', ['ofertas' => $ofertas]);
    }
}
