@props(['status'])

@php
    $nilai = $status instanceof \BackedEnum ? $status->value : (string) $status;

    $peta = [
        'menunggu' => ['label' => 'Menunggu', 'kelas' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        'sedang_diproses' => ['label' => 'Sedang Diproses', 'kelas' => 'bg-sky-50 text-sky-700 ring-sky-200'],
        'siap_diserahkan' => ['label' => 'Siap Diserahkan', 'kelas' => 'bg-teal-50 text-teal-700 ring-teal-200'],
        'diserahkan_sebagian' => ['label' => 'Diserahkan Sebagian', 'kelas' => 'bg-indigo-50 text-indigo-700 ring-indigo-200'],
        'selesai_diserahkan' => ['label' => 'Selesai Diserahkan', 'kelas' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'dirujuk_ke_apotek_luar' => ['label' => 'Dirujuk ke Apotek Luar', 'kelas' => 'bg-violet-50 text-violet-700 ring-violet-200'],
        'terhambat' => ['label' => 'Terhambat', 'kelas' => 'bg-rose-50 text-rose-700 ring-rose-200'],
    ];

    $info = $peta[$nilai] ?? ['label' => ucwords(str_replace('_', ' ', $nilai)), 'kelas' => 'bg-slate-100 text-slate-600 ring-slate-200'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset '.$info['kelas']]) }}>
    <span class="size-1.5 rounded-full bg-current opacity-70"></span>
    {{ $info['label'] }}
</span>
