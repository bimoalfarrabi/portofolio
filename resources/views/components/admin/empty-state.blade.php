@props([
    'colspan' => 1,
    'message' => 'Belum ada data.',
    'action' => null,
    'actionLabel' => null,
    'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
])

<tr>
    <td colspan="{{ $colspan }}" class="px-5 py-16 text-center">
        <div class="flex flex-col items-center gap-3">
            <span class="flex size-12 items-center justify-center rounded-full bg-surface-2">
                <svg class="size-6 text-ink-mute" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                </svg>
            </span>
            <p class="text-sm text-ink-mute">{{ $message }}</p>
            @if ($action && $actionLabel)
                <a href="{{ $action }}" class="mt-1 text-sm font-medium text-ink-soft underline decoration-line underline-offset-4 hover:text-ink">{{ $actionLabel }}</a>
            @endif
        </div>
    </td>
</tr>
