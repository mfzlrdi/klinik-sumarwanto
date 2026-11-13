@php
    $namaRoute = request()->route()?->getName() ?? '';
    $aktifUntuk = fn (array $pola): bool => collect($pola)->contains(fn ($p) => str_starts_with($namaRoute, $p));

    $menuUtama = [
        ['route' => 'dashboard', 'icon' => 'home', 'label' => 'Dashboard', 'pola' => ['dashboard']],
        ['route' => 'pasien.index', 'icon' => 'users', 'label' => 'Data Pasien', 'pola' => ['pasien']],
        ['route' => 'kunjungan.index', 'icon' => 'clipboard-list', 'label' => 'Antrean & Kunjungan', 'pola' => ['kunjungan', 'pemeriksaan']],
    ];

    $menuLayanan = [];
    if ($pengguna->memilikiPeran('resepsionis', 'administrator')) {
        $menuLayanan[] = ['route' => 'kunjungan.create', 'icon' => 'user-plus', 'label' => 'Pendaftaran', 'pola' => ['kunjungan.create']];
        $menuLayanan[] = ['route' => 'pemenuhan.index', 'icon' => 'package-check', 'label' => 'Tindak Lanjut Obat', 'pola' => ['pemenuhan']];
    }
    if ($pengguna->memilikiPeran('dokter', 'administrator')) {
        $menuLayanan[] = ['route' => 'resep.index', 'icon' => 'pill', 'label' => 'Resep', 'pola' => ['resep']];
    }
    if ($pengguna->memilikiPeran('administrator', 'dokter')) {
        $menuLayanan[] = ['route' => 'laporan.index', 'icon' => 'bar-chart-3', 'label' => 'Laporan', 'pola' => ['laporan']];
    }

    $menuAdmin = [];
    if ($pengguna->memilikiPeran('administrator')) {
        $menuAdmin = [
            ['route' => 'obat.index', 'icon' => 'pill-bottle', 'label' => 'Katalog Obat', 'pola' => ['obat']],
            ['route' => 'pengguna.index', 'icon' => 'user-cog', 'label' => 'Pengguna', 'pola' => ['pengguna']],
            ['route' => 'dokter.index', 'icon' => 'stethoscope', 'label' => 'Data Dokter', 'pola' => ['dokter']],
            ['route' => 'pengaturan.index', 'icon' => 'settings', 'label' => 'Pengaturan', 'pola' => ['pengaturan']],
            ['route' => 'catatan-aktivitas.index', 'icon' => 'scroll-text', 'label' => 'Catatan Aktivitas', 'pola' => ['catatan-aktivitas']],
        ];
    }

    $logoIkon = $pengaturanSistem['logo_ikon'] ?? 'activity';
    $logoNama = $pengaturanSistem['logo_nama'] ?? 'Praktik Dokter';
    $logoSubjudul = $pengaturanSistem['logo_subjudul'] ?? 'dr. Sumarwanto';
@endphp

<aside id="sidebar" data-sidebar class="sidebar-panel no-print fixed inset-y-0 left-0 z-40 flex flex-col bg-teal-solid text-teal-50 shadow-[8px_0_30px_rgba(8,60,56,.18)]">
    <a href="{{ route('dashboard') }}" class="sidebar-brand flex h-[72px] shrink-0 items-center gap-3 border-b border-white/10 px-5 focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-teal-200" title="{{ $logoNama }} — {{ $logoSubjudul }}">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-teal-100 ring-1 ring-white/15">
            <i data-lucide="{{ $logoIkon }}" class="size-[21px]"></i>
        </span>
        <span class="sidebar-brand-text leading-tight">
            <span class="block text-[13px] font-bold tracking-[-.02em] text-white">{{ $logoNama }}</span>
            <span class="mt-0.5 block text-[10.5px] font-medium text-teal-200/80">{{ $logoSubjudul }}</span>
        </span>
    </a>

    <div class="sidebar-user flex items-center gap-3 border-b border-white/10 px-5 py-4">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-[12px] font-bold text-teal-50 ring-1 ring-white/15" title="{{ $pengguna->nama_lengkap }}">
            {{ collect(explode(' ', $pengguna->nama_lengkap))->take(2)->map(fn ($b) => mb_strtoupper(mb_substr($b, 0, 1)))->implode('') }}
        </span>
        <span class="sidebar-user-meta min-w-0 flex-1">
            <span class="block truncate text-[12px] font-semibold text-white">{{ $pengguna->nama_lengkap }}</span>
            <span class="mt-0.5 block truncate text-[10.5px] text-teal-200/75">{{ $pengguna->namaPeran() }}</span>
        </span>
        <span class="sidebar-status relative flex size-2 shrink-0" title="Akun aktif">
            <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-300 opacity-60"></span>
            <span class="relative inline-flex size-2 rounded-full bg-emerald-400"></span>
        </span>
    </div>

    <nav class="sidebar-scrollbar flex-1 space-y-6 overflow-y-auto overflow-x-hidden px-3 py-5" aria-label="Navigasi utama">
        <div>
            <p class="sidebar-section-title mb-2 px-3 text-[10px] font-semibold uppercase tracking-[.14em] text-teal-200/50">Menu Utama</p>
            <ul class="space-y-1">
                @foreach ($menuUtama as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}" @class(['sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-[12.5px] font-medium', 'bg-white/15 text-white shadow-sm' => $aktifUntuk($item['pola']), 'text-teal-100/80 hover:bg-white/10 hover:text-white' => ! $aktifUntuk($item['pola'])]) aria-current="{{ $aktifUntuk($item['pola']) ? 'page' : 'false' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="sidebar-icon size-[17px] shrink-0 {{ $aktifUntuk($item['pola']) ? 'text-emerald-300' : 'text-teal-200/70' }}"></i>
                            <span class="sidebar-label truncate">{{ $item['label'] }}</span>
                            @if ($aktifUntuk($item['pola']))
                                <span class="sidebar-indicator ml-auto h-[18px] w-1 rounded-full bg-emerald-300"></span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        @if (count($menuLayanan))
            <div>
                <p class="sidebar-section-title mb-2 px-3 text-[10px] font-semibold uppercase tracking-[.14em] text-teal-200/50">Layanan</p>
                <ul class="space-y-1">
                    @foreach ($menuLayanan as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}" @class(['sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-[12.5px] font-medium', 'bg-white/15 text-white shadow-sm' => $aktifUntuk($item['pola']), 'text-teal-100/80 hover:bg-white/10 hover:text-white' => ! $aktifUntuk($item['pola'])]) aria-current="{{ $aktifUntuk($item['pola']) ? 'page' : 'false' }}">
                                <i data-lucide="{{ $item['icon'] }}" class="sidebar-icon size-[17px] shrink-0 {{ $aktifUntuk($item['pola']) ? 'text-emerald-300' : 'text-teal-200/70' }}"></i>
                                <span class="sidebar-label truncate">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (count($menuAdmin))
            <div>
                <p class="sidebar-section-title mb-2 px-3 text-[10px] font-semibold uppercase tracking-[.14em] text-teal-200/50">Administrasi</p>
                <ul class="space-y-1">
                    @foreach ($menuAdmin as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}" @class(['sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-[12.5px] font-medium', 'bg-white/15 text-white shadow-sm' => $aktifUntuk($item['pola']), 'text-teal-100/80 hover:bg-white/10 hover:text-white' => ! $aktifUntuk($item['pola'])]) aria-current="{{ $aktifUntuk($item['pola']) ? 'page' : 'false' }}">
                                <i data-lucide="{{ $item['icon'] }}" class="sidebar-icon size-[17px] shrink-0 {{ $aktifUntuk($item['pola']) ? 'text-emerald-300' : 'text-teal-200/70' }}"></i>
                                <span class="sidebar-label truncate">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </nav>

    <div class="border-t border-white/10 px-3 py-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Keluar dari aplikasi" class="sidebar-link flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-[12.5px] font-medium text-teal-100/80 transition-colors hover:bg-red-500/15 hover:text-red-100 focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-teal-200">
                <i data-lucide="log-out" class="sidebar-icon size-[17px]"></i>
                <span class="sidebar-label">Keluar dari aplikasi</span>
            </button>
        </form>
    </div>
</aside>
