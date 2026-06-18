@props(['name', 'label', 'checked' => false])

<label class="inline-flex items-center gap-3 border border-line bg-surface-2 px-4 py-3 text-sm text-ink-soft">
    <input type="checkbox" name="{{ $name }}" value="1" @checked($checked) class="size-4 rounded-none border-ink text-ink focus:ring-ink">
    <span class="font-mono text-xs uppercase tracking-[0.18em]">{{ $label }}</span>
</label>
