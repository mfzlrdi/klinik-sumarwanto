@props(['status'])

@php
    $nilai = $status instanceof \BackedEnum ? $status->value : (string) $status;

    $peta = [
        'draf' => ['label' => 'Draf', 'kelas' => 'bg-slate-100 text-slate-600 ring-slate-200'],
        'difinalisasi' => ['label' => 'Difinalisasi', 'kelas' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'kelas' => 'bg-rose-50 text-rose-700 ring-rose-200'],
    ];

    $info = $peta[$nilai] ?? ['label' => ucwords(str_replace('_', ' ', $nilai)), 'kelas' => 'bg-slate-100 text-slate-600 ring-slate-200'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset '.$info['kelas']]) }}>
    <span class="size-1.5 rounded-full bg-current opacity-70"></span>
    {{ $info['label'] }}
</span>
