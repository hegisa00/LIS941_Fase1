<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterEmpresaController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->dashboardRoute());
        }

        return view('auth.register-empresa');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre_empresa' => ['required', 'string', 'max:150'],
            'nit' => ['required', 'regex:/^\d{4}-\d{6}-\d{3}-\d$/', 'unique:empresas,nit'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'usuario' => ['required', 'string', 'min:4', 'max:30', 'alpha_dash', 'unique:users,usuario'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'usuario' => $datos['usuario'],
                'email' => $datos['email'],
                'password' => $datos['password'],
                'rol' => User::ROL_EMPRESA,
            ]);

            $usuario->empresa()->create([
                'nombre' => $datos['nombre_empresa'],
                'nit' => $datos['nit'],
                'direccion' => $datos['direccion'],
                'telefono' => $datos['telefono'],
                'estado' => Empresa::PENDIENTE,
            ]);
        });

        // No se inicia sesión automáticamente: primero un administrador debe aprobar la solicitud.
        return redirect()->route('empresa.login')
            ->with('status', 'Tu solicitud fue enviada. Un administrador la revisará y te notificaremos el resultado.');
    }
}
