<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsCanEditRegionMidleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $region = $request->route('region');
        if (!Auth::user()->isCanEditRegion($region)) {
            abort(403,'Access denied');
        }
        return $next($request);
    }
}
