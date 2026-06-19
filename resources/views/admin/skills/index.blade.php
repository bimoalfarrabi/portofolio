@extends('layouts.admin')

@section('title', 'Skills')
@section('breadcrumb', 'CMS / Skills')
@section('heading', 'Kelola Skills')

@section('actions')
    <a href="{{ route('admin.skills.create') }}" class="inline-flex items-center gap-2 border border-ink bg-ink px-4 py-2.5 font-mono text-xs uppercase tracking-[0.18em] text-surface-1 transition-colors hover:bg-accent hover:border-accent">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Skill
    </a>
@endsection

@section('content')
    @if ($skills->isEmpty())
        <div class="overflow-hidden border border-line bg-surface-1">
            <x-admin.empty-state
                :colspan="4"
                message="Belum ada skill."
                :action="route('admin.skills.create')"
                action-label="Tambah skill pertama"
                icon="M13 10V3L4 14h7v7l9-11h-7z"
            />
        </div>
    @else
        {{-- Save indicator --}}
        <div id="reorder-status" class="mb-4 hidden items-center gap-2 font-mono text-[10px] uppercase tracking-[0.18em]">
            <span id="reorder-saving" class="hidden items-center gap-1.5 text-ink-mute">
                <svg class="size-3 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Menyimpan urutan...
            </span>
            <span id="reorder-saved" class="hidden items-center gap-1.5 text-emerald-600">
                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Urutan tersimpan
            </span>
            <span id="reorder-error" class="hidden items-center gap-1.5 text-red-600">
                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Gagal menyimpan
            </span>
        </div>

        <div class="space-y-6" id="skills-groups">
            @foreach ($skills as $category => $group)
                <div class="overflow-hidden border border-line bg-surface-1">
                    {{-- Category header --}}
                    <div class="flex items-center justify-between border-b border-line bg-surface-2 px-5 py-3">
                        <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute">{{ $category }}</p>
                        <span class="font-mono text-[10px] text-ink-faint">{{ $group->count() }} skill</span>
                    </div>

                    {{-- Skill rows (sortable) --}}
                    <ul class="divide-y divide-line" data-sortable>
                        @foreach ($group as $skill)
                            <li class="group flex items-center gap-3 px-5 py-3.5 transition-colors hover:bg-surface-2"
                                data-skill-id="{{ $skill->id }}">

                                {{-- Drag handle --}}
                                <span class="drag-handle shrink-0 cursor-grab text-ink-faint transition-colors hover:text-ink-mute active:cursor-grabbing" title="Drag untuk reorder">
                                    <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm6 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm6 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm6 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                    </svg>
                                </span>

                                {{-- Name + icon key --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2.5">
                                        <span class="font-medium text-ink">{{ $skill->name }}</span>
                                        @if ($skill->icon)
                                            <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">{{ $skill->icon }}</span>
                                        @else
                                            <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">auto</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Status --}}
                                @if ($skill->is_active)
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-surface-2 px-2.5 py-0.5 text-xs font-medium text-ink-mute">
                                        <span class="size-1.5 rounded-full bg-line"></span>
                                        Inactive
                                    </span>
                                @endif

                                {{-- Actions --}}
                                <div class="flex shrink-0 items-center gap-1 transition-opacity sm:opacity-60 sm:group-hover:opacity-100 sm:focus-within:opacity-100">
                                    <a href="{{ route('admin.skills.edit', $skill) }}" class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-surface-2 hover:text-ink-soft" title="Edit">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" data-confirm="Skill akan dihapus permanen. Tindakan ini tidak dapat dibatalkan." data-confirm-title="Hapus Skill">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-red-50 hover:text-red-600" title="Delete">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
<script>
(function () {
    // Load SortableJS from CDN
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js';
    script.onload = initSortable;
    document.head.appendChild(script);

    const statusEl   = document.getElementById('reorder-status');
    const savingEl   = document.getElementById('reorder-saving');
    const savedEl    = document.getElementById('reorder-saved');
    const errorEl    = document.getElementById('reorder-error');
    let saveTimer    = null;

    function showStatus(state) {
        statusEl.classList.remove('hidden');
        statusEl.classList.add('flex');
        savingEl.classList.toggle('hidden', state !== 'saving');
        savingEl.classList.toggle('flex', state === 'saving');
        savedEl.classList.toggle('hidden', state !== 'saved');
        savedEl.classList.toggle('flex', state === 'saved');
        errorEl.classList.toggle('hidden', state !== 'error');
        errorEl.classList.toggle('flex', state === 'error');
    }

    function collectOrder() {
        return [...document.querySelectorAll('[data-skill-id]')].map(el => el.dataset.skillId);
    }

    async function saveOrder() {
        showStatus('saving');
        try {
            const res = await fetch('{{ route('admin.skills.reorder') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ order: collectOrder() }),
            });
            showStatus(res.ok ? 'saved' : 'error');
        } catch (e) {
            showStatus('error');
        }
    }

    function onDragEnd() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveOrder, 400);
    }

    function initSortable() {
        document.querySelectorAll('[data-sortable]').forEach(list => {
            Sortable.create(list, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'opacity-40',
                chosenClass: 'bg-surface-2',
                dragClass: 'shadow-lg',
                // Allow dragging across groups so skills can move between categories
                group: 'skills',
                onEnd: onDragEnd,
            });
        });
    }
})();
</script>
@endpush
