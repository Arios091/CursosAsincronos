<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    protected const ALLOWED_ROLES = ['docente', 'estudiante', 'admin', 'admin_global'];

    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, self::ALLOWED_ROLES, true)) {
            abort(403, 'Rol de usuario invalido.');
        }

        foreach ($roles as $role) {
            if (!in_array($role, self::ALLOWED_ROLES, true)) {
                continue;
            }
            if ($userRole === $role) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos para acceder a esta seccion.');
    }
}
