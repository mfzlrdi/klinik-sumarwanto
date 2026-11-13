<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resep {{ $resep->nomor_resep }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css'])
    <style>
        :root { font-family: 'Instrument Sans', sans-serif; }
        body { background: #f1f4f3; }
        .lembar { max-width: 720px; margin: 0 auto; }
        @media print { body { background: #fff; } .lembar { box-shadow: none; } }
    </style>
</head>
<body class="min-h-screen py-10 antialiased">
    <div class="lembar overflow-hidden rounded-2xl bg-white shadow-[0_18px_50px_rgba(15,40,35,.14)]">
        <div class="flex items-center justify-between border-b-2 border-teal-solid px-8 py-6">
            <div>
                <p class="text-[11px] font-medium uppercase tracking-[.16em] text-teal-600">Praktik Dokter Umum</p>
                <h1 class="mt-1 text-[19px] font-bold text-slate-800">dr. Sumarwanto</h1>
            </div>
            <div class="text-right">
                <p class="font-mono text-[14px] font-bold text-teal-700">{{ $resep->nomor_resep }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">{{ $resep->dibuat_pada?->translatedFormat('j F Y H:i') }}</p>
            </div>
        </div>

        <div class="px-8 py-6">
            <div class="grid grid-cols-2 gap-4 text-[13px]">
                <div><span class="block text-[11px] uppercase tracking-[.08em] text-slate-400">Pasien</span><span class="font-semibold text-slate-800">{{ $resep->kunjungan->pasien->nama_lengkap }}</span></div>
                <div><span class="block text-[11px] uppercase tracking-[.08em] text-slate-400">No. Rekam Medis</span><span class="font-mono font-semibold text-slate-800">{{ $resep->kunjungan->pasien->nomor_rekam_medis }}</span></div>
            </div>

            <div class="mt-6 overflow-hidden rounded-xl border border-slate-200">
                <table class="w-full text-left text-[12.5px]">
                    <thead>
                        <tr class="bg-slate-50 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-4 py-3">Obat</th>
                            <th class="px-4 py-3">Dosis</th>
                            <th class="px-4 py-3">Frekuensi</th>
                            <th class="px-4 py-3">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($resep->rincianResep as $rincian)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-slate-700">{{ $rincian->nama_obat_saat_diresepkan }}</span>
                                    <span class="block text-[11px] text-slate-400">{{ collect([$rincian->kekuatan_saat_diresepkan, $rincian->bentuk_sediaan_saat_diresepkan])->filter()->implode(' · ') }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $rincian->dosis ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $rincian->frekuensi_pemberian ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $rincian->jumlah_diresepkan }} {{ $rincian->satuan_jumlah }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @php $petunjuk = $resep->rincianResep->pluck('petunjuk_penggunaan')->filter(); @endphp
            @if ($petunjuk->isNotEmpty())
                <div class="mt-5 rounded-xl bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-semibold uppercase tracking-[.08em] text-slate-400">Petunjuk penggunaan</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-[12.5px] text-slate-600">
                        @foreach ($petunjuk as $p)<li>{{ $p }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-8 flex items-end justify-between">
                <div class="text-[11px] text-slate-400">Terima kasih,<br>semoga lekas sembuh.</div>
                <div class="text-center">
                    <p class="text-[12px] font-semibold text-slate-700">dr. Sumarwanto</p>
                    <div class="mt-16 border-t border-slate-300 pt-1 text-[10.5px] text-slate-400">Tanda tangan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="no-print mt-6 text-center">
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-5 py-2.5 text-[12.5px] font-semibold text-white shadow-sm hover:bg-teal-deep">Cetak Resep</button>
        <a href="{{ route('resep.show', $resep) }}" class="ml-2 inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-[12.5px] font-semibold text-slate-600 hover:bg-slate-50">Selesai</a>
    </div>
</body>
</html>
