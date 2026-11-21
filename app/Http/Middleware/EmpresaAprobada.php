<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Solo deja pasar a empresas cuya solicitud ya fue aprobada por un administrador.
 * Se usa después de RoleMiddleware:empresa.
 */
class EmpresaAprobada
{
    public function handle(Request $request, Closure $next): Response
    {
        $empresa = $request->user()?->empresa;

        if (! $empresa || ! $empresa->estaAprobada()) {
            return redirect()->route('empresa.dashboard')
                ->with('error', 'Tu empresa debe estar aprobada para publicar ofertas.');
        }

        return $next($request);
    }
}
