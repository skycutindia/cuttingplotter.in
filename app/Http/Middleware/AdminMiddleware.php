<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasAnyRole(['super-admin', 'admin', 'content-manager', 'seo-executive', 'sales-team', 'support-team'])) {
            abort(403, 'Unauthorized access to admin panel.');
        }

        return $next($request);
    }
}
