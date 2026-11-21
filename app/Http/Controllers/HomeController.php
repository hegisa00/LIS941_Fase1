<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public const ORDENES = [
        'recientes' => 'Más recientes',
        'descuento' => 'Mayor descuento',
        'precio_asc' => 'Precio: menor a mayor',
        'precio_desc' => 'Precio: mayor a menor',
        'vence' => 'Próximas a vencer',
    ];

    public function index(Request $request)
    {
        $datos = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'orden' => ['nullable', 'in:'.implode(',', array_keys(self::ORDENES))],
        ]);
        $q = trim($datos['q'] ?? '');
        $orden = $datos['orden'] ?? 'recientes';

        $consulta = Oferta::with('empresa')
            ->withSum('compras as vendidos', 'cantidad')
            ->visibles();

        if ($q !== '') {
            // Se escapan los comodines de LIKE para que el texto se busque literalmente.
            $like = '%'.addcslashes($q, '%_\\').'%';
            $consulta->where(function ($w) use ($like) {
                $w->where('titulo', 'like', $like)
                    ->orWhere('descripcion', 'like', $like)
                    ->orWhereHas('empresa', fn ($e) => $e->where('nombre', 'like', $like));
            });
        }

        match ($orden) {
            'descuento' => $consulta->orderByRaw('(1 - precio_oferta / precio_regular) DESC'),
            'precio_asc' => $consulta->orderBy('precio_oferta'),
            'precio_desc' => $consulta->orderByDesc('precio_oferta'),
            'vence' => $consulta->orderBy('fecha_fin'),
            default => $consulta->latest(),
        };

        return view('home', [
            'ofertas' => $consulta->paginate(9)->withQueryString(),
            'q' => $q,
            'orden' => $orden,
            'ordenes' => self::ORDENES,
        ]);
    }
}
