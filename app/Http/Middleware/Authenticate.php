<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $request->headers->set('Accept', 'application/json');
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($credentials = $request->cookie('credentials')) {
            $request->headers->set('Authorization', 'Bearer ' . $credentials);
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
