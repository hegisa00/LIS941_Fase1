<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

/**
 * Recuperación de contraseña para los tres tipos de usuario (todos están en la tabla users).
 */
class PasswordResetController extends Controller
{
    public function solicitud()
    {
        return view('auth.forgot-password');
    }

    public function enviarEnlace(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $enlaceDemo = null;

        $estado = Password::sendResetLink(
            $request->only('email'),
            function ($usuario, $token) use (&$enlaceDemo) {
                // En desarrollo local también se muestra el enlace en pantalla (útil sin servidor de correo).
                if (app()->isLocal()) {
                    $enlaceDemo = route('password.reset', ['token' => $token, 'email' => $usuario->email]);
                }

                try {
                    $usuario->sendPasswordResetNotification($token);
                } catch (\Throwable $e) {
                    report($e);
                }

                return Password::RESET_LINK_SENT;
            }
        );

        if ($estado !== Password::RESET_LINK_SENT) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($estado)]);
        }

        return back()->with('status', __($estado))->with('enlace_demo', $enlaceDemo);
    }

    public function formulario(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);

        $usuarioActualizado = null;

        $estado = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $usuario, string $password) use (&$usuarioActualizado) {
                $usuario->forceFill([
                    'password' => $password, // el modelo la cifra automáticamente (cast "hashed")
                    'remember_token' => Str::random(60),
                ])->save();

                $usuarioActualizado = $usuario;

                event(new PasswordReset($usuario));
            }
        );

        if ($estado === Password::PASSWORD_RESET && $usuarioActualizado) {
            return redirect()->route($usuarioActualizado->loginRoute())->with('status', __($estado));
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($estado)]);
    }
}
