<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', 'https://amralkhozondar.com') // maybe put this into the .env file so you can change the URL in production.
            ->header('Access-Control-Allow-Methods', '*') // or specify `'GET, POST, PUT, DELETE'` etc as the second parameter if you want to restrict the methods that are allowed.
            ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization'); // or add your headers.
    }
}
