@props(['href'])

<a href="{{ $href }}" class="inline-flex items-center gap-2 border border-line bg-surface-1 px-4 py-2.5 text-sm font-medium text-ink-soft transition-colors duration-150 hover:border-ink hover:text-ink">
    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>
    {{ $slot->isEmpty() ? 'Kembali' : $slot }}
</a>
