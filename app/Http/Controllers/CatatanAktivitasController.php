<?php

namespace App\Http\Controllers;

use App\Models\CatatanAktivitas;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatatanAktivitasController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless($request->user()->memilikiPeran('administrator'), 403);

        $aktivitas = CatatanAktivitas::query()
            ->with('pengguna')
            ->latest('dibuat_pada')
            ->paginate(20);

        return view('catatan-aktivitas.index', ['aktivitas' => $aktivitas]);
    }
}
