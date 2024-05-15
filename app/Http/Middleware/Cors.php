<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, DELETE, PUT');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // Log::info('CORS Headers:', [
        //     'Access-Control-Allow-Origin' => $response->headers->get('Access-Control-Allow-Origin'),
        //     'Access-Control-Allow-Methods' => $response->headers->get('Access-Control-Allow-Methods'),
        //     'Access-Control-Allow-Headers' => $response->headers->get('Access-Control-Allow-Headers'),
        // ]);

        return $response;
    }
}
