<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AdministradorController extends Controller
{
    public function index()
    {
        return view('admin.administradores.index', [
            'administradores' => Administrador::with('user')->orderBy('nombre_completo')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.administradores.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:150'],
            'usuario' => ['required', 'string', 'min:4', 'max:30', 'alpha_dash', 'unique:users,usuario'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'usuario' => $datos['usuario'],
                'email' => $datos['email'],
                'password' => $datos['password'],
                'rol' => User::ROL_ADMIN,
            ]);

            $usuario->administrador()->create([
                'nombre_completo' => $datos['nombre_completo'],
            ]);
        });

        return redirect()->route('admin.administradores.index')
            ->with('status', 'Administrador registrado correctamente.');
    }
}
