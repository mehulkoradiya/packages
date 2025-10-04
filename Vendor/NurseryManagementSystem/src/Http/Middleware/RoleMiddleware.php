<?php

namespace Vendor\NurseryManagementSystem\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();
        if (! $user) {
            throw new AccessDeniedHttpException('Unauthorized');
        }

        // If spatie/permission exists use it; otherwise check 'role' column
        if (method_exists($user, 'hasRole')) {
            if (! $user->hasRole($role)) {
                throw new AccessDeniedHttpException('Forbidden');
            }
        } else {
            if (($user->role ?? null) !== $role) {
                throw new AccessDeniedHttpException('Forbidden');
            }
        }

        return $next($request);
    }
}
