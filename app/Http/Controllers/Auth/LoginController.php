<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /** Muestra el formulario de login para el rol indicado en la ruta (cliente, empresa o admin). */
    public function mostrar(string $rol)
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->dashboardRoute());
        }

        return view('auth.login', ['rol' => $rol]);
    }

    /** Valida las credenciales; solo entra quien tenga el rol correspondiente a este login. */
    public function autenticar(Request $request, string $rol)
    {
        $datos = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Se acepta el usuario o el correo electrónico.
        $campo = filter_var($datos['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'usuario';

        $credenciales = [
            $campo => $datos['login'],
            'password' => $datos['password'],
            'rol' => $rol,
        ];

        if (! Auth::attempt($credenciales, $request->boolean('recordar'))) {
            throw ValidationException::withMessages([
                'login' => 'Las credenciales no coinciden con ninguna cuenta de este tipo.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route(Auth::user()->dashboardRoute());
    }

    public function salir(Request $request)
    {
        $destino = Auth::check() ? Auth::user()->loginRoute() : 'home';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($destino)->with('status', 'Cerraste sesión correctamente.');
    }
}
