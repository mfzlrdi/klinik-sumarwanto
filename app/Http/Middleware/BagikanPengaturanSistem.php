<?php

namespace App\Http\Middleware;

use App\Models\PengaturanSistem;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class BagikanPengaturanSistem
{
    /**
     * Membagikan konfigurasi sistem ke seluruh view sekali per request
     * agar sidebar, navbar, dan landing page konsisten.
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('pengaturanSistem', PengaturanSistem::query()
            ->pluck('nilai_pengaturan', 'nama_pengaturan')
            ->all());

        return $next($request);
    }
}
