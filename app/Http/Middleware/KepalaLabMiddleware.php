<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KepalaLabMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'kepala_lab') {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}