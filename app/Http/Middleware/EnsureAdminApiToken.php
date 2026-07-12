<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class EnsureAdminApiToken
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user() instanceof Admin, 403, 'Admin API token required.');

        return $next($request);
    }
}
