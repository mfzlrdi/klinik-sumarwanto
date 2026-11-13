@extends('layouts.app')

@section('title', 'Catatan Aktivitas')

@section('content')
    <x-page-header judul="Catatan Aktivitas" deskripsi="Jejak audit perubahan penting pada sistem." />

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        @if ($aktivitas->isEmpty())
            <x-empty-state icon="scroll-text" title="Belum ada aktivitas" description="Aktivitas penting akan tercatat di sini." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-5 py-3">Waktu</th>
                            <th class="px-5 py-3">Pengguna</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3">Entitas</th>
                            <th class="px-5 py-3">Data Terkait</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($aktivitas as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">{{ $item->dibuat_pada?->translatedFormat('j F Y H:i') }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-700">{{ $item->pengguna?->nama_lengkap ?? 'Sistem' }}</td>
                                <td class="px-5 py-3.5"><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">{{ $item->jenis_aktivitas?->label() ?? '-' }}</span></td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">{{ $item->nama_entitas }}</td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">#{{ $item->id_data_terkait }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $aktivitas->links() }}</div>
        @endif
    </section>
@endsection
