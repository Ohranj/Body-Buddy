<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class AddTimestampToRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $day = Carbon::now();

        if ($request->has('timestamp')) {
            $day = Carbon::createFromTimestamp($request->timestamp);
        }

        $request->merge(['day' => $day]);

        return $next($request);
    }
}
