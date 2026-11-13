<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d5c56">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Praktik Dokter dr. Sumarwanto</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

@php
    $pengguna = $pengguna ?? auth()->user()?->loadMissing('peran');
@endphp

<body class="min-h-screen bg-[#f6f9f8] text-slate-800 antialiased">
    <div data-sidebar-overlay class="no-print fixed inset-0 z-30 hidden bg-slate-900/50 opacity-0 backdrop-blur-[2px] transition-opacity" aria-hidden="true"></div>

    <x-sidebar :pengguna="$pengguna" />

    <div data-konten class="konten-utama min-h-screen">
        <x-navbar :pengguna="$pengguna" />

        <main class="mx-auto max-w-[1440px] px-4 pb-12 pt-6 sm:px-7 lg:px-9 lg:pt-8">
            @yield('content')
        </main>
    </div>

    <x-toast />

    <x-confirm-modal />

    @stack('scripts')
</body>

</html>
