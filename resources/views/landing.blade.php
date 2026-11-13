@php
    $pengaturan = $pengaturanSistem ?? [];
    $namaPraktik = $pengaturan['nama_praktik'] ?? 'Praktik Dokter Umum dr. Sumarwanto';
    $alamat = $pengaturan['alamat_praktik'] ?? 'Jl. Kesehatan No. 10, Yogyakarta';
    $telepon = $pengaturan['nomor_telepon'] ?? '(0274) 123456';
    $jam = $pengaturan['jam_operasional'] ?? 'Senin–Sabtu, 08.00–20.00 WIB';
    $logoIkon = $pengaturan['logo_ikon'] ?? 'activity';
    $logoNama = $pengaturan['logo_nama'] ?? 'Praktik Dokter';
    $logoSubjudul = $pengaturan['logo_subjudul'] ?? 'dr. Sumarwanto';
    $tagline = $pengaturan['tagline'] ?? 'Administrasi lebih teratur, pelayanan terasa lebih dekat.';
    $deskripsi = $pengaturan['deskripsi_singkat'] ?? 'Praktik dokter umum yang melayani pemeriksaan, konsultasi, dan tindak lanjut kesehatan keluarga dengan pencatatan yang rapi.';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d5c56">
    <meta name="description" content="{{ $deskripsi }}">
    <title>{{ $namaPraktik }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { font-family: 'Instrument Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        .landing-dots {
            background-image: radial-gradient(rgba(13, 92, 86, .16) 1px, transparent 1px);
            background-size: 22px 22px;
        }
        .landing-reveal { opacity: 0; transform: translateY(18px); transition: opacity .6s ease, transform .6s ease; }
        .landing-reveal.landing-tampil { opacity: 1; transform: translateY(0); }
    </style>
</head>

<body class="bg-white text-slate-800 antialiased">

    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-[72px] max-w-[1240px] items-center justify-between gap-4 px-4 sm:px-8">
            <a href="{{ route('beranda') }}" class="flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-xl bg-teal-solid text-white shadow-sm">
                    <i data-lucide="{{ $logoIkon }}" class="size-[21px]"></i>
                </span>
                <span class="leading-tight">
                    <span class="block text-[13px] font-bold tracking-[-.02em] text-slate-800">{{ $logoNama }}</span>
                    <span class="mt-0.5 block text-[10.5px] font-medium text-slate-400">{{ $logoSubjudul }}</span>
                </span>
            </a>

            <nav class="hidden items-center gap-7 text-[12.5px] font-medium text-slate-600 lg:flex" aria-label="Navigasi utama">
                <a href="#layanan" class="transition-colors hover:text-teal-700">Layanan</a>
                <a href="#dokter" class="transition-colors hover:text-teal-700">Dokter</a>
                <a href="#alur" class="transition-colors hover:text-teal-700">Alur Pelayanan</a>
                <a href="#jadwal" class="transition-colors hover:text-teal-700">Jadwal</a>
                <a href="#kontak" class="transition-colors hover:text-teal-700">Kontak</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('antrean.monitor') }}" target="_blank" rel="noopener" class="hidden items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 transition-colors hover:border-teal-300 hover:text-teal-700 sm:inline-flex">
                    <i data-lucide="monitor" class="size-4"></i>Monitor Antrean
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                    <i data-lucide="log-in" class="size-4"></i>Masuk Aplikasi
                </a>
            </div>
        </div>
    </header>

    <main>
        {{-- HERO --}}
        <section class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 landing-dots opacity-60"></div>
            <div class="pointer-events-none absolute -right-32 -top-32 size-[36rem] rounded-full bg-teal-50"></div>
            <div class="pointer-events-none absolute -left-40 bottom-0 size-[28rem] rounded-full bg-emerald-50/70"></div>

            <div class="relative mx-auto grid max-w-[1240px] items-center gap-12 px-4 py-16 sm:px-8 lg:grid-cols-2 lg:py-24">
                <div class="landing-reveal">
                    <span class="inline-flex items-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-3.5 py-1.5 text-[11px] font-semibold uppercase tracking-[.1em] text-teal-700">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>Praktik Dokter Umum
                    </span>
                    <h1 class="mt-5 text-[36px] font-bold leading-[1.08] tracking-[-.045em] text-slate-900 sm:text-[48px] lg:text-[56px]">
                        {{ $tagline }}
                    </h1>
                    <p class="mt-5 max-w-xl text-[14.5px] leading-7 text-slate-500">{{ $deskripsi }}</p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-5 py-3 text-[13px] font-semibold text-white shadow-[0_10px_24px_rgba(13,92,86,.22)] transition-all hover:-translate-y-0.5 hover:bg-teal-deep">
                            <i data-lucide="log-in" class="size-4"></i>Masuk Aplikasi
                        </a>
                        <a href="{{ route('antrean.monitor') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-[13px] font-semibold text-slate-700 transition-all hover:-translate-y-0.5 hover:border-teal-300 hover:text-teal-700">
                            <i data-lucide="monitor" class="size-4"></i>Lihat Antrean
                        </a>
                    </div>

                    <div class="mt-10 grid max-w-lg grid-cols-3 gap-4">
                        <div>
                            <p class="text-[22px] font-bold tracking-tight text-slate-800">10+</p>
                            <p class="mt-0.5 text-[11px] text-slate-400">Layanan terpadu</p>
                        </div>
                        <div class="border-l border-slate-200 pl-4">
                            <p class="text-[22px] font-bold tracking-tight text-slate-800">1 Sistem</p>
                            <p class="mt-0.5 text-[11px] text-slate-400">Data pasien terpusat</p>
                        </div>
                        <div class="border-l border-slate-200 pl-4">
                            <p class="text-[22px] font-bold tracking-tight text-slate-800">24/7</p>
                            <p class="mt-0.5 text-[11px] text-slate-400">Antrean transparan</p>
                        </div>
                    </div>
                </div>

                <div class="landing-reveal relative">
                    <div class="absolute -right-4 -top-4 hidden size-24 rounded-2xl border border-teal-200 sm:block"></div>
                    <div class="relative overflow-hidden rounded-[26px] bg-teal-solid p-6 text-white shadow-[0_30px_70px_rgba(8,60,56,.28)] sm:p-8">
                        <div class="pointer-events-none absolute -right-16 -top-16 size-56 rounded-full border border-white/10"></div>
                        <div class="pointer-events-none absolute -bottom-20 -left-10 size-64 rounded-full border border-white/[.07]"></div>

                        <div class="relative z-10 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex size-9 items-center justify-center rounded-xl bg-white/10 text-teal-100 ring-1 ring-white/15"><i data-lucide="activity" class="size-5"></i></span>
                                <span class="text-[12px] font-semibold">{{ $logoNama }}</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-2.5 py-1 text-[10px] font-semibold text-teal-100"><span class="size-1.5 animate-pulse rounded-full bg-emerald-300"></span>Live</span>
                        </div>

                        <div class="relative z-10 mt-10 text-center">
                            <p class="text-[11px] font-semibold uppercase tracking-[.22em] text-teal-200/70">Sedang Dipanggil</p>
                            <p class="mt-2 text-[64px] font-black leading-none tracking-tighter text-white">A-012</p>
                            <p class="mt-2 text-[15px] font-semibold text-teal-50">Budi S.</p>
                            <p class="mt-1 text-[11px] text-teal-200/70">Silakan menuju ruang pemeriksaan</p>
                        </div>

                        <div class="relative z-10 mt-8 grid grid-cols-3 gap-2 text-center">
                            <div class="rounded-xl bg-white/10 px-2 py-2.5"><p class="text-[17px] font-bold">3</p><p class="text-[9.5px] text-teal-200/70">Menunggu</p></div>
                            <div class="rounded-xl bg-white/10 px-2 py-2.5"><p class="text-[17px] font-bold">1</p><p class="text-[9.5px] text-teal-200/70">Dipanggil</p></div>
                            <div class="rounded-xl bg-white/10 px-2 py-2.5"><p class="text-[17px] font-bold">2</p><p class="text-[9.5px] text-teal-200/70">Selesai</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- LAYANAN --}}
        <section id="layanan" class="mx-auto max-w-[1240px] px-4 py-16 sm:px-8 lg:py-20">
            <div class="landing-reveal mx-auto max-w-2xl text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[.16em] text-teal-600">Layanan</p>
                <h2 class="mt-2 text-[28px] font-bold tracking-[-.03em] text-slate-900 sm:text-[34px]">Pelayanan yang tertata, dari pendaftaran sampai tindak lanjut</h2>
                <p class="mt-3 text-[13.5px] leading-6 text-slate-500">Satu alur kerja digital untuk resepsionis, dokter, dan administrasi praktik.</p>
            </div>

            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['ikon' => 'stethoscope', 'judul' => 'Pemeriksaan Umum', 'teks' => 'Pemeriksaan fisik, tanda vital, diagnosis, dan tindakan tercatat rapi.'],
                    ['ikon' => 'clipboard-list', 'judul' => 'Antrean Digital', 'teks' => 'Nomor antrean otomatis dan monitor layar ruang tunggu secara langsung.'],
                    ['ikon' => 'pill', 'judul' => 'Resep & Tindak Lanjut', 'teks' => 'Resep multi-obat dengan pemantauan penyerahan obat yang terlacak.'],
                    ['ikon' => 'file-text', 'judul' => 'Surat Keterangan', 'teks' => 'Surat keterangan sakit resmi, bernomor, dan siap dicetak.'],
                ] as $layanan)
                    <article class="landing-reveal rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:-translate-y-1 hover:border-teal-200 hover:shadow-[0_16px_36px_rgba(15,40,35,.08)]">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600"><i data-lucide="{{ $layanan['ikon'] }}" class="size-5"></i></span>
                        <h3 class="mt-4 text-[14px] font-semibold text-slate-800">{{ $layanan['judul'] }}</h3>
                        <p class="mt-1.5 text-[12px] leading-6 text-slate-500">{{ $layanan['teks'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- DOKTER --}}
        <section id="dokter" class="border-y border-slate-100 bg-slate-50/60">
            <div class="mx-auto grid max-w-[1240px] items-center gap-10 px-4 py-16 sm:px-8 lg:grid-cols-2 lg:py-20">
                <div class="landing-reveal order-2 lg:order-1">
                    <p class="text-[11px] font-semibold uppercase tracking-[.16em] text-teal-600">Tentang Dokter</p>
                    <h2 class="mt-2 text-[28px] font-bold tracking-[-.03em] text-slate-900 sm:text-[34px]">Ditangani langsung oleh dokter yang berpengalaman</h2>
                    <p class="mt-4 text-[13.5px] leading-7 text-slate-500">dr. Sumarwanto melayani pemeriksaan umum, konsultasi keluhan ringan hingga sedang, serta tindak lanjut pengobatan dengan pendekatan yang ramah dan personal.</p>

                    <ul class="mt-6 space-y-3">
                        @foreach ([
                            'Pemeriksaan menyeluruh dan penjelasan yang mudah dipahami',
                            'Catatan rekam medis elektronik yang rapi dan aman',
                            'Resep dan tindak lanjut obat yang terkoordinasi',
                            'Surat keterangan sakit resmi untuk keperluan izin',
                        ] as $poin)
                            <li class="flex items-start gap-2.5 text-[12.5px] text-slate-600">
                                <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"><i data-lucide="check" class="size-3.5"></i></span>
                                {{ $poin }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="landing-reveal order-1 lg:order-2">
                    <div class="overflow-hidden rounded-[26px] border border-slate-200 bg-white p-6 shadow-[0_20px_50px_rgba(15,40,35,.08)] sm:p-8">
                        <div class="flex items-center gap-4">
                            <span class="flex size-16 items-center justify-center rounded-2xl bg-teal-solid text-[20px] font-bold text-white">DS</span>
                            <div>
                                <p class="text-[17px] font-bold text-slate-800">dr. Sumarwanto</p>
                                <p class="mt-0.5 text-[12px] text-slate-400">Dokter Umum · Praktik Mandiri</p>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-slate-50 px-4 py-3"><p class="text-[10px] uppercase tracking-[.08em] text-slate-400">Jam Praktik</p><p class="mt-1 text-[12px] font-semibold text-slate-700">{{ $jam }}</p></div>
                            <div class="rounded-xl bg-slate-50 px-4 py-3"><p class="text-[10px] uppercase tracking-[.08em] text-slate-400">Lokasi</p><p class="mt-1 text-[12px] font-semibold text-slate-700">{{ $alamat }}</p></div>
                        </div>
                        <div class="mt-4 flex items-center gap-2 rounded-xl bg-teal-50 px-4 py-3 text-[11.5px] text-teal-700">
                            <i data-lucide="shield-check" class="size-4 shrink-0"></i>Data pasien terjaga dan hanya diakses pengguna berwenang.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ALUR --}}
        <section id="alur" class="mx-auto max-w-[1240px] px-4 py-16 sm:px-8 lg:py-20">
            <div class="landing-reveal mx-auto max-w-2xl text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[.16em] text-teal-600">Alur Pelayanan</p>
                <h2 class="mt-2 text-[28px] font-bold tracking-[-.03em] text-slate-900 sm:text-[34px]">Empat langkah sederhana</h2>
            </div>

            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['nomor' => '01', 'judul' => 'Pendaftaran', 'teks' => 'Resepsionis mendaftarkan pasien dan mencetak nomor antrean.'],
                    ['nomor' => '02', 'judul' => 'Menunggu', 'teks' => 'Pasien menunggu hingga nomor dipanggil melalui monitor dan suara.'],
                    ['nomor' => '03', 'judul' => 'Pemeriksaan', 'teks' => 'Dokter memeriksa, mencatat rekam medis, dan memberikan resep.'],
                    ['nomor' => '04', 'judul' => 'Tindak Lanjut', 'teks' => 'Obat diserahkan dan status tindak lanjut tercatat sampai selesai.'],
                ] as $langkah)
                    <div class="landing-reveal relative rounded-2xl border border-slate-200 bg-white p-5">
                        <span class="text-[26px] font-black tracking-tighter text-teal-200">{{ $langkah['nomor'] }}</span>
                        <h3 class="mt-1 text-[14px] font-semibold text-slate-800">{{ $langkah['judul'] }}</h3>
                        <p class="mt-1.5 text-[12px] leading-6 text-slate-500">{{ $langkah['teks'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- JADWAL & KONTAK --}}
        <section id="jadwal" class="border-y border-slate-100 bg-slate-50/60">
            <div class="mx-auto grid max-w-[1240px] gap-4 px-4 py-16 sm:px-8 lg:grid-cols-2 lg:py-20">
                <div class="landing-reveal rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                    <span class="flex size-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600"><i data-lucide="calendar-days" class="size-5"></i></span>
                    <h2 class="mt-4 text-[20px] font-bold tracking-tight text-slate-900">Jadwal Praktik</h2>
                    <p class="mt-2 text-[13px] leading-6 text-slate-500">Silakan datang sesuai jam operasional. Nomor antrean dibuat saat pendaftaran di lokasi.</p>
                    <div class="mt-5 flex items-center justify-between rounded-xl bg-teal-50 px-4 py-3.5">
                        <span class="text-[12px] font-semibold text-teal-800">{{ $jam }}</span>
                        <i data-lucide="clock" class="size-4 text-teal-600"></i>
                    </div>
                    <a href="{{ route('antrean.monitor') }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-[12px] font-semibold text-teal-700 hover:underline">
                        <i data-lucide="monitor" class="size-4"></i>Pantau antrean saat ini
                    </a>
                </div>

                <div id="kontak" class="landing-reveal rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                    <span class="flex size-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="map-pin" class="size-5"></i></span>
                    <h2 class="mt-4 text-[20px] font-bold tracking-tight text-slate-900">Kontak & Lokasi</h2>
                    <p class="mt-2 text-[13px] leading-6 text-slate-500">{{ $namaPraktik }}</p>

                    <dl class="mt-5 space-y-3.5">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="map-pin" class="size-4"></i></span>
                            <div><dt class="text-[10px] uppercase tracking-[.08em] text-slate-400">Alamat</dt><dd class="mt-0.5 text-[12.5px] text-slate-700">{{ $alamat }}</dd></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="phone" class="size-4"></i></span>
                            <div><dt class="text-[10px] uppercase tracking-[.08em] text-slate-400">Telepon</dt><dd class="mt-0.5 text-[12.5px] text-slate-700">{{ $telepon }}</dd></div>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mx-auto max-w-[1240px] px-4 py-16 sm:px-8 lg:py-20">
            <div class="landing-reveal relative overflow-hidden rounded-[26px] bg-teal-solid px-6 py-10 text-center text-white sm:px-12 sm:py-14">
                <div class="pointer-events-none absolute -right-20 -top-20 size-64 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-16 size-72 rounded-full border border-white/[.07]"></div>
                <h2 class="relative z-10 text-[26px] font-bold tracking-[-.03em] sm:text-[32px]">Siap melayani dengan lebih tertata</h2>
                <p class="relative z-10 mx-auto mt-2 max-w-xl text-[13px] leading-6 text-teal-100/80">Masuk ke aplikasi untuk mengelola pendaftaran, antrean, pemeriksaan, dan tindak lanjut pasien.</p>
                <a href="{{ route('login') }}" class="relative z-10 mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-[13px] font-semibold text-teal-700 shadow-sm transition-transform hover:-translate-y-0.5">
                    <i data-lucide="log-in" class="size-4"></i>Masuk Aplikasi
                </a>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200">
        <div class="mx-auto flex max-w-[1240px] flex-col items-center justify-between gap-3 px-4 py-7 text-center sm:flex-row sm:px-8 sm:text-left">
            <div class="flex items-center gap-2.5">
                <span class="flex size-8 items-center justify-center rounded-lg bg-teal-solid text-white"><i data-lucide="{{ $logoIkon }}" class="size-4"></i></span>
                <span class="text-[12px] font-semibold text-slate-700">{{ $logoNama }}</span>
            </div>
            <p class="text-[11px] text-slate-400">© {{ now()->year }} {{ $namaPraktik }}. Sistem informasi administrasi pelayanan pasien.</p>
        </div>
    </footer>

    <script>
        (() => {
            const elemen = document.querySelectorAll('.landing-reveal');
            if (!('IntersectionObserver' in window)) {
                elemen.forEach((el) => el.classList.add('landing-tampil'));
                return;
            }
            const pengamat = new IntersectionObserver((entri) => {
                entri.forEach((e) => {
                    if (e.isIntersecting) {
                        e.target.classList.add('landing-tampil');
                        pengamat.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12 });
            elemen.forEach((el) => pengamat.observe(el));
        })();
    </script>
</body>

</html>
