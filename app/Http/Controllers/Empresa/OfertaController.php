<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\Request;

/**
 * Publicación y edición de ofertas por parte de la empresa aprobada.
 * Una empresa solo puede ver y modificar SUS ofertas.
 */
class OfertaController extends Controller
{
    public function index(Request $request)
    {
        $ofertas = $request->user()->empresa->ofertas()
            ->withSum('compras as vendidos', 'cantidad')
            ->latest()
            ->paginate(10);

        return view('empresa.ofertas.index', ['ofertas' => $ofertas]);
    }

    public function create()
    {
        return view('empresa.ofertas.form', ['oferta' => new Oferta(['estado' => 'disponible'])]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        $request->user()->empresa->ofertas()->create($datos);

        return redirect()->route('empresa.ofertas.index')->with('status', 'La oferta fue publicada.');
    }

    public function edit(Request $request, int $oferta)
    {
        return view('empresa.ofertas.form', ['oferta' => $this->propia($request, $oferta)]);
    }

    public function update(Request $request, int $oferta)
    {
        $oferta = $this->propia($request, $oferta);
        $datos = $this->validar($request, $oferta);

        $oferta->update($datos);

        return redirect()->route('empresa.ofertas.index')->with('status', 'La oferta fue actualizada.');
    }

    /** Busca la oferta dentro de las de la empresa; si es de otra, responde 404. */
    private function propia(Request $request, int $id): Oferta
    {
        return $request->user()->empresa->ofertas()->findOrFail($id);
    }

    private function validar(Request $request, ?Oferta $oferta = null): array
    {
        // Al editar no se puede bajar el tope por debajo de lo que ya se vendió.
        $minimoCupones = max(1, $oferta?->cuponesVendidos() ?? 0);

        return $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'precio_regular' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
            'precio_oferta' => ['required', 'numeric', 'gt:0', 'lt:precio_regular'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'fecha_limite_canje' => ['required', 'date', 'after_or_equal:fecha_fin'],
            'cantidad_cupones' => ['nullable', 'integer', 'min:'.$minimoCupones, 'max:1000000'],
            'descripcion' => ['required', 'string', 'max:2000'],
            'estado' => ['required', 'in:disponible,no_disponible'],
        ], [
            'precio_oferta.lt' => 'El precio de oferta debe ser menor que el precio regular.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la de inicio.',
            'fecha_limite_canje.after_or_equal' => 'La fecha límite para canjear no puede ser anterior al fin de la oferta.',
            'cantidad_cupones.min' => $oferta
                ? 'La cantidad no puede ser menor que los cupones ya vendidos ('.$minimoCupones.').'
                : 'La cantidad debe ser al menos 1 (o déjala vacía para no limitar).',
        ], [
            'titulo' => 'título',
            'precio_regular' => 'precio regular',
            'precio_oferta' => 'precio de oferta',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin' => 'fecha de fin',
            'fecha_limite_canje' => 'fecha límite de canje',
            'cantidad_cupones' => 'cantidad de cupones',
            'descripcion' => 'descripción',
        ]);
    }
}
