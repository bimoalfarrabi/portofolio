@props(['cancel', 'label' => 'Simpan'])

<div class="flex items-center gap-3 pt-2">
    <button type="submit" class="inline-flex items-center gap-2 bg-ink px-6 py-3 text-sm font-medium uppercase tracking-[0.16em] text-surface-1 transition-colors duration-150 hover:bg-accent">
        <span class="font-mono text-xs">[OK]</span>
        {{ $label }}
    </button>
    <a href="{{ $cancel }}" class="px-4 py-3 font-mono text-xs uppercase tracking-[0.16em] text-ink-mute transition-colors hover:text-ink">Batal</a>
</div>
