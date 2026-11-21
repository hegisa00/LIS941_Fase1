<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Cupon;
use App\Models\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Compra simulada de cupones, factura e historial del cliente.
 */
class CompraController extends Controller
{
    /** Historial de compras del cliente autenticado. */
    public function index(Request $request)
    {
        $compras = $request->user()->cliente->compras()
            ->with(['oferta.empresa', 'cupones.compra.oferta'])
            ->latest()
            ->paginate(8);

        return view('cliente.compras.index', ['compras' => $compras]);
    }

    public function create(Request $request, int $oferta)
    {
        $oferta = $this->ofertaComprable($oferta);
        $maximo = $this->maximoComprable($request, $oferta);

        return view('cliente.compras.create', [
            'oferta' => $oferta,
            'maximo' => $maximo,
            'yaComprados' => $this->yaComprados($request, $oferta),
        ]);
    }

    public function store(Request $request, int $oferta)
    {
        $oferta = $this->ofertaComprable($oferta);
        $cliente = $request->user()->cliente;

        // El número de tarjeta se acepta con o sin espacios/guiones.
        $request->merge([
            'numero_tarjeta' => preg_replace('/[\s-]+/', '', (string) $request->input('numero_tarjeta')),
        ]);

        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'between:1,'.Oferta::MAX_POR_CLIENTE],
            'titular' => ['required', 'string', 'min:3', 'max:100'],
            'numero_tarjeta' => ['required', 'digits:16'],
            'vencimiento' => ['required', 'string', function (string $campo, mixed $valor, \Closure $falla) {
                if (! preg_match('/^(0[1-9]|1[0-2])\s*\/\s*(\d{2})$/', $valor, $m)) {
                    $falla('El vencimiento debe tener el formato MM/AA (por ejemplo 08/29).');

                    return;
                }
                // La tarjeta sirve hasta el último día del mes indicado.
                $vence = Carbon::create(2000 + (int) $m[2], (int) $m[1], 1)->endOfMonth();
                if ($vence->lt(today())) {
                    $falla('La tarjeta está vencida.');
                }
            }],
            'cvv' => ['required', 'digits_between:3,4'],
        ], [
            'cantidad.between' => 'Puedes comprar de 1 a '.Oferta::MAX_POR_CLIENTE.' cupones por oferta.',
            'numero_tarjeta.digits' => 'El número de tarjeta debe tener 16 dígitos.',
            'cvv.digits_between' => 'El código de seguridad (CVV) debe tener 3 o 4 dígitos.',
        ], [
            'numero_tarjeta' => 'número de tarjeta',
            'titular' => 'titular',
            'vencimiento' => 'fecha de vencimiento',
            'cvv' => 'código de seguridad',
        ]);

        $cantidad = (int) $datos['cantidad'];

        $compra = DB::transaction(function () use ($oferta, $cliente, $cantidad, $datos) {
            // Se bloquea la oferta para que dos compras simultáneas no excedan el stock ni el límite por cliente.
            $oferta = Oferta::whereKey($oferta->id)->lockForUpdate()->firstOrFail();

            $yaComprados = (int) Compra::where('cliente_id', $cliente->id)
                ->where('oferta_id', $oferta->id)
                ->sum('cantidad');

            if ($yaComprados + $cantidad > Oferta::MAX_POR_CLIENTE) {
                $quedan = max(0, Oferta::MAX_POR_CLIENTE - $yaComprados);
                throw ValidationException::withMessages([
                    'cantidad' => $quedan === 0
                        ? 'Ya compraste el máximo de '.Oferta::MAX_POR_CLIENTE.' cupones de esta oferta.'
                        : 'Solo puedes comprar '.$quedan.' cupón(es) más de esta oferta (máximo '.Oferta::MAX_POR_CLIENTE.' por cliente).',
                ]);
            }

            $restantes = $oferta->cuponesRestantes();
            if ($restantes !== null && $cantidad > $restantes) {
                throw ValidationException::withMessages([
                    'cantidad' => $restantes === 0
                        ? 'Esta oferta se agotó.'
                        : 'Solo quedan '.$restantes.' cupón(es) disponibles de esta oferta.',
                ]);
            }

            $compra = Compra::create([
                'cliente_id' => $cliente->id,
                'oferta_id' => $oferta->id,
                'numero_factura' => $this->numeroFactura(),
                'cantidad' => $cantidad,
                'precio_unitario' => $oferta->precio_oferta,
                'total' => round($oferta->precio_oferta * $cantidad, 2),
                'tarjeta_ultimos4' => substr($datos['numero_tarjeta'], -4), // jamás se guarda el número completo ni el CVV
            ]);

            for ($i = 0; $i < $cantidad; $i++) {
                $compra->cupones()->create(['codigo' => $this->codigoUnico(), 'estado' => 'disponible']);
            }

            return $compra;
        });

        return redirect()->route('compras.factura', $compra->id)
            ->with('status', '¡Compra realizada! Aquí está tu factura con los códigos de tus cupones.');
    }

    public function factura(Request $request, int $compra)
    {
        $compra = Compra::with(['cliente.user', 'oferta.empresa', 'cupones.compra.oferta'])->findOrFail($compra);

        // Solo el cliente que hizo la compra puede ver su factura.
        abort_unless($compra->cliente_id === $request->user()->cliente->id, 403, 'Esta factura no te pertenece.');

        return view('cliente.compras.factura', ['compra' => $compra]);
    }

    /** La oferta debe estar visible para el público (disponible, vigente, empresa aprobada). */
    private function ofertaComprable(int $id): Oferta
    {
        return Oferta::with('empresa')
            ->withSum('compras as vendidos', 'cantidad')
            ->visibles()
            ->findOrFail($id);
    }

    private function yaComprados(Request $request, Oferta $oferta): int
    {
        return (int) Compra::where('cliente_id', $request->user()->cliente->id)
            ->where('oferta_id', $oferta->id)
            ->sum('cantidad');
    }

    /** Cuántos cupones más puede comprar este cliente: tope por cliente y existencias. */
    private function maximoComprable(Request $request, Oferta $oferta): int
    {
        $porCliente = Oferta::MAX_POR_CLIENTE - $this->yaComprados($request, $oferta);
        $restantes = $oferta->cuponesRestantes();

        return max(0, $restantes === null ? $porCliente : min($porCliente, $restantes));
    }

    private function numeroFactura(): string
    {
        do {
            $numero = 'FAC-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        } while (Compra::where('numero_factura', $numero)->exists());

        return $numero;
    }

    /** Código único por cupón, con formato CUP-XXXXX-XXXXX. */
    private function codigoUnico(): string
    {
        do {
            $codigo = 'CUP-'.strtoupper(Str::random(5)).'-'.strtoupper(Str::random(5));
        } while (Cupon::where('codigo', $codigo)->exists());

        return $codigo;
    }
}
