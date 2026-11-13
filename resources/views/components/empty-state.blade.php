@props(['icon' => 'inbox', 'title' => 'Belum ada data', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center px-6 py-12 text-center']) }}>
    <span class="flex size-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
        <i data-lucide="{{ $icon }}" class="size-5"></i>
    </span>
    <h3 class="mt-3 text-[13px] font-semibold text-slate-700">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1.5 max-w-[300px] text-[11.5px] leading-5 text-slate-400">{{ $description }}</p>
    @endif
    @if (isset($slot) && trim($slot->toHtml()) !== '')
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
