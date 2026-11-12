<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeriksaPeran
{
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        abort_unless($request->user()?->memilikiPeran(...$peran), 403);

        return $next($request);
    }
}
