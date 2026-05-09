<?php

namespace App\Http\Middleware;

use App\Models\Region;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminInRegionMidleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $region = $request->route('region');
        if (!Auth::user()->isAdminInRegion($region)) {
            abort(403,'Access denied admin');
        }
        return $next($request);
    }
}
