@props(['icon', 'label', 'value', 'warna' => 'teal', 'keterangan' => null])

@php
    $warnaPeta = [
        'teal' => 'bg-teal-50 text-teal-600',
        'biru' => 'bg-sky-50 text-sky-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'ungu' => 'bg-violet-50 text-violet-600',
        'merah' => 'bg-rose-50 text-rose-600',
    ];
    $ikonWarna = $warnaPeta[$warna] ?? $warnaPeta['teal'];
@endphp

<article {{ $attributes->merge(['class' => 'group rounded-2xl border border-slate-200 bg-white p-4 transition-all hover:-translate-y-0.5 hover:border-teal-200 hover:shadow-[0_12px_28px_rgba(15,40,35,.08)] sm:p-5']) }}>
    <div class="flex items-center justify-between">
        <span class="text-[12px] font-medium text-slate-500">{{ $label }}</span>
        <span class="flex size-9 items-center justify-center rounded-xl {{ $ikonWarna }} transition-transform group-hover:scale-110">
            <i data-lucide="{{ $icon }}" class="size-[18px]"></i>
        </span>
    </div>
    <div class="mt-3 flex items-end justify-between">
        <span class="text-[26px] font-semibold leading-none tracking-[-.04em] text-slate-800">{{ $value }}</span>
        @if ($keterangan)
            <span class="text-[10.5px] text-slate-400">{{ $keterangan }}</span>
        @endif
    </div>
</article>
