<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Kunjungan</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css'])
    <style>
        :root { font-family: 'Instrument Sans', sans-serif; }
        body { background: #f1f4f3; }
        .lembar { max-width: 820px; margin: 0 auto; }
        @media print { body { background: #fff; } .lembar { box-shadow: none; } }
    </style>
</head>
<body class="min-h-screen py-10 antialiased">
    <div class="lembar overflow-hidden rounded-2xl bg-white shadow-[0_18px_50px_rgba(15,40,35,.14)]">
        <div class="border-b-2 border-teal-solid px-8 py-6 text-center">
            <p class="text-[11px] font-medium uppercase tracking-[.16em] text-teal-600">Sistem Informasi Praktik Dokter</p>
            <h1 class="mt-1 text-[20px] font-bold text-slate-800">Laporan Kunjungan Pasien</h1>
            <p class="mt-1 text-[12px] text-slate-500">Periode {{ \Carbon\Carbon::parse($dari)->translatedFormat('j F Y') }} — {{ \Carbon\Carbon::parse($sampai)->translatedFormat('j F Y') }}</p>
        </div>

        <div class="px-8 py-6">
            <div class="grid grid-cols-3 gap-4">
                <div class="rounded-xl bg-teal-50 px-4 py-3 text-center">
                    <p class="text-[20px] font-bold text-teal-700">{{ $ringkasan['total_kunjungan'] }}</p>
                    <p class="text-[10.5px] font-medium uppercase tracking-[.06em] text-teal-600">Total Kunjungan</p>
                </div>
                <div class="rounded-xl bg-emerald-50 px-4 py-3 text-center">
                    <p class="text-[20px] font-bold text-emerald-700">{{ $ringkasan['selesai'] }}</p>
                    <p class="text-[10.5px] font-medium uppercase tracking-[.06em] text-emerald-600">Selesai</p>
                </div>
                <div class="rounded-xl bg-rose-50 px-4 py-3 text-center">
                    <p class="text-[20px] font-bold text-rose-700">{{ $ringkasan['dibatalkan'] }}</p>
                    <p class="text-[10.5px] font-medium uppercase tracking-[.06em] text-rose-600">Dibatalkan</p>
                </div>
            </div>

            <table class="mt-6 w-full text-left text-[12.5px]">
                <thead>
                    <tr class="border-b-2 border-slate-200 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                        <th class="py-2 pr-3">No</th>
                        <th class="py-2 pr-3">Tanggal</th>
                        <th class="py-2 pr-3">Antrean</th>
                        <th class="py-2 pr-3">Pasien</th>
                        <th class="py-2 pr-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($kunjungan as $i => $item)
                        <tr>
                            <td class="py-2.5 pr-3 text-slate-400">{{ $i + 1 }}</td>
                            <td class="py-2.5 pr-3 text-slate-600">{{ $item->tanggal_kunjungan->translatedFormat('j M Y') }}</td>
                            <td class="py-2.5 pr-3 font-mono font-bold text-teal-700">{{ $item->kodeAntrean() }}</td>
                            <td class="py-2.5 pr-3 font-semibold text-slate-700">{{ $item->pasien->nama_lengkap }}</td>
                            <td class="py-2.5 pr-3 text-slate-600">{{ $item->status_kunjungan?->label() ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-8 border-t border-slate-200 pt-4 text-center text-[11px] text-slate-400">
                Dicetak pada {{ now()->translatedFormat('j F Y H:i') }} · Praktik Dokter Umum dr. Sumarwanto
            </div>
        </div>
    </div>

    <div class="no-print mt-6 text-center">
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-5 py-2.5 text-[12.5px] font-semibold text-white shadow-sm hover:bg-teal-deep">Cetak</button>
        <a href="{{ route('laporan.index') }}" class="ml-2 inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-[12.5px] font-semibold text-slate-600 hover:bg-slate-50">Kembali</a>
    </div>
</body>
</html>
