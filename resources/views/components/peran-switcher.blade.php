@props(['pengguna', 'peranAktif'])

@php
    $daftarPeran = $pengguna->peran->pluck('nama_peran')->unique()->values()->all();
@endphp

@if (count($daftarPeran) > 1)
    <div class="no-print inline-flex flex-wrap items-center gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
        <span class="px-2 text-[10.5px] font-medium text-slate-400">Lihat sebagai</span>
        @foreach ($daftarPeran as $peran)
            <a href="{{ route('dashboard', ['sebagai' => $peran]) }}" @class(['rounded-lg px-3 py-1.5 text-[11.5px] font-semibold transition-colors', 'bg-teal-solid text-white shadow-sm' => $peran === $peranAktif, 'text-slate-500 hover:bg-slate-100 hover:text-slate-700' => $peran !== $peranAktif])>
                {{ ucfirst($peran) }}
            </a>
        @endforeach
    </div>
@endif
