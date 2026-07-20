<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/'.ltrim($request->getPathInfo(), '/');

        if ($path === '/') {
            return $next($request);
        }

        $redirect = Redirect::active()
            ->where(function ($q) use ($path) {
                $q->where('from_url', $path)
                    ->orWhere('from_url', ltrim($path, '/'));
            })
            ->first();

        if ($redirect) {
            $redirect->increment('hits');
            $to = str_starts_with($redirect->to_url, 'http')
                ? $redirect->to_url
                : url($redirect->to_url);

            return redirect($to, $redirect->status_code);
        }

        return $next($request);
    }
}
