<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Illuminate\Http\Request;

class RequestLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info("Request Logged\n".sprintf("~~~~~~~~~\n%s~~~~~~~~~", (string) $request));
        return $next($request);
    }
}
