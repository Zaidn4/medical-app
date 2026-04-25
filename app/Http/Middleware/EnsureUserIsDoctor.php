<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDoctor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and is a doctor
        if (! $request->user() || ! $request->user()->isDoctor()) {
            abort(403, 'Accès refusé. Cette section est réservée aux médecins.');
        }

        return $next($request);
    }
}
