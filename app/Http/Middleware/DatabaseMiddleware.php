<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$slave): Response
    {
        $slave_name = '';
        $slave_list = [
            'mysql',
            'slave1',
            'slave2',
            'slave3',
        ];
        if ($request->isMethod('get')) { // Read
            DB::setDefaultConnection($slave_list[$slave]); // Or choose a random slave
        } else { // Write
            DB::setDefaultConnection('mysql');
        }
        return $next($request);
    }
}
