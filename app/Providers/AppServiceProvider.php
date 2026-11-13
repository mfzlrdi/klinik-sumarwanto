<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Menyediakan pengguna aktif beserta perannya ke seluruh view
        // agar navigasi dan otorisasi tampilan konsisten di semua halaman.
        View::composer('*', function ($view): void {
            $view->with('pengguna', auth()->user()?->loadMissing('peran'));
        });
    }
}
