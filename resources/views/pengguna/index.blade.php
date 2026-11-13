@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <x-page-header judul="Pengguna" deskripsi="Kelola akun pengguna dan penetapan peran.">
        <a href="{{ route('pengguna.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-teal-deep">
            <i data-lucide="plus" class="size-4"></i>Tambah Pengguna
        </a>
    </x-page-header>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        @if ($daftarPengguna->isEmpty())
            <x-empty-state icon="user-cog" title="Belum ada pengguna" description="Tambahkan akun pengguna untuk mulai mengelola sistem." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Peran</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($daftarPengguna as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex size-8 items-center justify-center rounded-full bg-teal-solid text-[10px] font-semibold text-white">{{ collect(explode(' ', $item->nama_lengkap))->take(2)->map(fn ($b) => mb_strtoupper(mb_substr($b, 0, 1)))->implode('') }}</span>
                                        <span class="font-semibold text-slate-700">{{ $item->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">{{ $item->alamat_email }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($item->peran as $peran)
                                            <span class="rounded-full bg-teal-50 px-2 py-0.5 text-[10px] font-semibold text-teal-700">{{ ucfirst($peran->nama_peran) }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-3.5"><span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $item->status_aktif ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('pengguna.edit', $item) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-amber-50 hover:text-amber-600" title="Edit"><i data-lucide="pencil" class="size-4"></i></a>
                                        @if ($item->id_pengguna !== auth()->id())
                                            <form method="POST" action="{{ route('pengguna.destroy', $item) }}" data-confirm data-confirm-title="Hapus pengguna" data-confirm-message="Akun {{ $item->nama_lengkap }} akan dihapus." data-confirm-label="Ya, hapus">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600" title="Hapus"><i data-lucide="trash-2" class="size-4"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $daftarPengguna->links() }}</div>
        @endif
    </section>
@endsection
