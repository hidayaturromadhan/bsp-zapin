<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $role = $request->session()->get('user_role');

        if (!$role || !in_array($role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}
