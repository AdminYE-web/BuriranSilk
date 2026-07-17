<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $admin = auth('admin')->user();

        if (! $admin || $admin->role !== 'super_admin') {
            abort(403);
        }

        return $next($request);
    }
}