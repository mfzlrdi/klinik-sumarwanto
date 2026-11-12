<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $pengguna = $request->user();

        abort_unless($pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator'), 403);

        return view('dashboard.index', [
            'pengguna' => $pengguna->loadMissing('peran'),
            'sapaan' => $this->sapaan(),
        ]);
    }

    private function sapaan(): string
    {
        return match (true) {
            now()->hour < 11 => 'Selamat pagi',
            now()->hour < 15 => 'Selamat siang',
            now()->hour < 19 => 'Selamat sore',
            default => 'Selamat malam',
        };
    }
}
