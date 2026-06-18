@props(['label'])

<div {{ $attributes->merge(['class' => 'border border-line bg-surface-1 p-6 shadow-sm']) }}>
    <p class="mb-5 font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute">{{ $label }}</p>
    {{ $slot }}
</div>
