<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddCustomHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->add([
            'Accept' => 'application/json',
            'Authorization'=>\Config::get('app.passport_token'),
            //dd()
            
        ]);
        return $next($request);
    }
}
