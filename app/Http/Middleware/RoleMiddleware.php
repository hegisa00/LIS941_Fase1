<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe una ruta a uno o más roles. Uso en rutas:
 *   ->middleware(RoleMiddleware::class.':admin')
 *   ->middleware(RoleMiddleware::class.':admin,empresa')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        // Sin sesión: se envía al login que corresponde a la zona solicitada.
        if (! $usuario) {
            $login = match ($roles[0] ?? 'cliente') {
                'admin' => 'admin.login',
                'empresa' => 'empresa.login',
                default => 'login',
            };

            return redirect()->route($login)->with('error', 'Debes iniciar sesión para continuar.');
        }

        // Con sesión pero con otro rol: acceso prohibido.
        if (! in_array($usuario->rol, $roles, true)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
