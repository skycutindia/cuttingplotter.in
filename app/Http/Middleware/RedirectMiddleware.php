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
        if (! in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $path = '/'.ltrim($request->getPathInfo(), '/');

        if ($path === '/' || $this->isProtectedPath($path)) {
            return $next($request);
        }

        $redirect = Redirect::active()
            ->where(function ($q) use ($path) {
                $q->where('from_url', $path)
                    ->orWhere('from_url', ltrim($path, '/'));
            })
            ->first();

        if ($redirect && ! $this->pointsAtCurrentUrl($request, $redirect->to_url, $path)) {
            $redirect->increment('hits');

            $to = str_starts_with($redirect->to_url, 'http')
                ? $redirect->to_url
                : url($redirect->to_url);

            return redirect($to, $redirect->status_code);
        }

        return $next($request);
    }

    /**
     * Auth and admin URLs must stay reachable. A redirect on these paths
     * would remove the only way to sign in and delete the redirect.
     */
    protected function isProtectedPath(string $path): bool
    {
        foreach ([
            '/admin',
            '/login',
            '/register',
            '/forgot-password',
            '/reset-password',
            '/confirm-password',
            '/logout',
            '/profile',
            '/email',
            '/up',
        ] as $protected) {
            if ($path === $protected || str_starts_with($path, $protected.'/')) {
                return true;
            }
        }

        return false;
    }

    protected function pointsAtCurrentUrl(Request $request, string $toUrl, string $path): bool
    {
        $to = str_starts_with($toUrl, 'http') ? $toUrl : url($toUrl);
        $parts = parse_url($to);

        if ($parts === false) {
            return false;
        }

        if (isset($parts['host']) && strcasecmp($parts['host'], $request->getHost()) !== 0) {
            return false;
        }

        $targetPath = '/'.ltrim($parts['path'] ?? '/', '/');

        return $targetPath === $path && ($parts['query'] ?? '') === ($request->getQueryString() ?? '');
    }
}
