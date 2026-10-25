<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmpresaController extends Controller
{
    private const FILTROS = ['pendiente', 'aprobada', 'rechazada', 'todas'];

    public function index(Request $request)
    {
        $estado = $request->query('estado', 'pendiente');
        if (! in_array($estado, self::FILTROS, true)) {
            $estado = 'pendiente';
        }

        $consulta = Empresa::with('user')->latest();
        if ($estado !== 'todas') {
            $consulta->where('estado', $estado);
        }

        $conteos = Empresa::selectRaw('estado, COUNT(*) AS total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return view('admin.empresas.index', [
            'empresas' => $consulta->paginate(10)->withQueryString(),
            'estado' => $estado,
            'conteos' => $conteos,
        ]);
    }

    /** Aprueba la solicitud y fija el % de comisión por cada cupón vendido. */
    public function aprobar(Request $request, Empresa $empresa)
    {
        if ($empresa->estaAprobada()) {
            return back()->with('error', 'Esta empresa ya fue aprobada.');
        }

        $datos = $request->validateWithBag('aprobar_'.$empresa->id, [
            'comision' => ['required', 'numeric', 'between:0,100', 'decimal:0,2'],
        ]);

        $empresa->update([
            'estado' => Empresa::APROBADA,
            'comision' => $datos['comision'],
            'motivo_rechazo' => null,
            'resuelta_por' => $request->user()->id,
            'fecha_resolucion' => now(),
        ]);

        $this->notificar(
            $empresa,
            'Tu empresa fue aprobada en La Cuponera SV',
            "Hola {$empresa->nombre},\n\nTu solicitud fue aprobada. Ya puedes iniciar sesión y publicar ofertas.\n"
            ."La comisión por cada cupón vendido es de {$datos['comision']}%.\n\nLa Cuponera SV"
        );

        return back()->with('status', "Empresa «{$empresa->nombre}» aprobada con una comisión de {$datos['comision']}%.");
    }

    /** Rechaza una solicitud pendiente indicando el motivo. */
    public function rechazar(Request $request, Empresa $empresa)
    {
        if ($empresa->estado !== Empresa::PENDIENTE) {
            return back()->with('error', 'Solo se pueden rechazar solicitudes pendientes.');
        }

        $datos = $request->validateWithBag('rechazar_'.$empresa->id, [
            'motivo_rechazo' => ['required', 'string', 'max:255'],
        ]);

        $empresa->update([
            'estado' => Empresa::RECHAZADA,
            'comision' => null,
            'motivo_rechazo' => $datos['motivo_rechazo'],
            'resuelta_por' => $request->user()->id,
            'fecha_resolucion' => now(),
        ]);

        $this->notificar(
            $empresa,
            'Tu solicitud en La Cuponera SV fue rechazada',
            "Hola {$empresa->nombre},\n\nTu solicitud fue rechazada.\nMotivo: {$datos['motivo_rechazo']}\n\nLa Cuponera SV"
        );

        return back()->with('status', "Solicitud de «{$empresa->nombre}» rechazada.");
    }

    /** Envía un correo simple; si el correo no está configurado, no interrumpe la operación. */
    private function notificar(Empresa $empresa, string $asunto, string $mensaje): void
    {
        try {
            Mail::raw($mensaje, function ($correo) use ($empresa, $asunto) {
                $correo->to($empresa->user->email)->subject($asunto);
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
