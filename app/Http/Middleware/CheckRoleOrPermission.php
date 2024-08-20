<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class CheckRoleOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @param  string  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role, $permission): Response
    {
        $user = Auth::user();

        try {
            if ($user->hasRole($role) || $user->hasPermissionTo($permission)) {
                return $next($request);
            }
        } catch (PermissionDoesNotExist $e) {
            return redirect()->route('admin')->with('error', 'El permiso especificado no existe.');
        }

        return redirect()->route('admin')->with('error', 'No tienes permiso para acceder a esta página.');
    }
}
