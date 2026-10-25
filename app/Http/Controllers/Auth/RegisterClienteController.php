<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterClienteController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->dashboardRoute());
        }

        return view('auth.register-cliente');
    }

    public function store(Request $request)
    {
        // Debe ser mayor de edad: la fecha de nacimiento no puede ser posterior a hace 18 años.
        $fechaMaxima = now()->subYears(18)->toDateString();

        $datos = $request->validate([
            'usuario' => ['required', 'string', 'min:4', 'max:30', 'alpha_dash', 'unique:users,usuario'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dui' => ['required', 'regex:/^\d{8}-\d$/', 'unique:clientes,dui'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:'.$fechaMaxima],
        ]);

        $usuario = DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'usuario' => $datos['usuario'],
                'email' => $datos['email'],
                'password' => $datos['password'],
                'rol' => User::ROL_CLIENTE,
            ]);

            $usuario->cliente()->create([
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'dui' => $datos['dui'],
                'fecha_nacimiento' => $datos['fecha_nacimiento'],
            ]);

            return $usuario;
        });

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('cliente.dashboard')
            ->with('status', '¡Registro completado! Ya puedes explorar los cupones disponibles.');
    }
}
