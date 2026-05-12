<?php

namespace App\Http\Middleware;

use App\Models\Region;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsCanEditRegionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $region = $request->route('region');
        if (Auth::user()->isCanEditRegion($region) or Auth::user()->isMainEngineerInRegion($region)) {
            return $next($request);
        }
        abort(403,'Access denied edit');
    }
}
