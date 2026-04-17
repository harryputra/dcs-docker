<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     * Instructs the browser to never cache the response so the back button requires a fresh fetch (and re-authentication if logged out).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Required headers to completely disable back-forward caching (BFCache)
        return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
