@extends('layouts.admin')

@section('title', 'Messages')
@section('breadcrumb', 'CMS / Messages')
@section('heading', 'Inbox Collab')

@section('content')
    @php
        $tabs = [
            ['key' => 'all', 'label' => 'All', 'count' => $totalCount],
            ['key' => 'unread', 'label' => 'Unread', 'count' => $unreadCount],
            ['key' => 'read', 'label' => 'Read', 'count' => $readCount],
        ];
    @endphp

    <div class="mb-5 flex flex-wrap items-center gap-2">
        @foreach ($tabs as $tab)
            @php($isActive = $filter === $tab['key'])
            <a href="{{ route('admin.messages.index', $tab['key'] === 'all' ? [] : ['filter' => $tab['key']]) }}"
               class="inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-medium transition-colors {{ $isActive ? 'border-ink bg-ink text-surface-1' : 'border-line bg-white text-ink-soft hover:border-ink hover:text-ink' }}">
                {{ $tab['label'] }}
                <span class="inline-flex min-w-[1.25rem] items-center justify-center rounded-full px-1.5 py-0.5 text-[10px] font-semibold {{ $isActive ? 'bg-white/15 text-white' : 'bg-surface-2 text-ink-soft' }}">
                    {{ $tab['count'] }}
                </span>
            </a>
        @endforeach
        <span class="ml-auto font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute">
            Showing {{ $messages->count() }} of {{ $messages->total() }}
        </span>
    </div>

    <div class="overflow-hidden border border-line bg-surface-1 shadow-sm">
        <div class="divide-y divide-line">
            @forelse ($messages as $message)
                <details class="group">
                    <summary class="flex cursor-pointer items-center justify-between gap-4 px-5 py-4 transition-colors hover:bg-surface-2">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="size-2 shrink-0 rounded-full {{ $message->is_read ? 'bg-line' : 'bg-emerald-500' }}"></span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-ink">{{ $message->name }}</p>
                                <p class="truncate font-mono text-xs text-ink-mute">{{ $message->email }}</p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            @if (! $message->mail_sent)
                                <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider text-amber-700">mail failed</span>
                            @endif
                            <span class="font-mono text-[10px] uppercase tracking-wider text-ink-mute">{{ $message->created_at?->format('d M H:i') }}</span>
                            <svg class="size-4 text-ink-mute transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </summary>
                    <div class="border-t border-line bg-surface-2 px-5 py-4">
                        <p class="whitespace-pre-line text-sm leading-7 text-ink-soft">{{ $message->message }}</p>
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <a href="mailto:{{ $message->email }}?subject=Re: collab message" class="inline-flex items-center gap-1.5 border border-ink bg-ink px-4 py-2 font-mono text-xs uppercase tracking-[0.18em] text-surface-1 transition-colors hover:bg-accent hover:border-accent">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h15a2.25 2.25 0 0 1 2.25 2.25v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6" />
                                </svg>
                                Balas via email
                            </a>
                            <form method="POST" action="{{ route('admin.messages.toggle-read', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1.5 border border-line bg-surface-1 px-4 py-2 text-xs font-medium text-ink-soft transition-colors hover:border-ink hover:text-ink">
                                    {{ $message->is_read ? 'Tandai belum dibaca' : 'Tandai sudah dibaca' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Pesan akan dihapus permanen." data-confirm-title="Hapus Pesan">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 border border-line bg-surface-1 px-4 py-2 text-xs font-medium text-ink-mute transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                    Hapus
                                </button>
                            </form>
                            @if ($message->ip_address)
                                <span class="ml-auto font-mono text-[10px] text-ink-mute">IP {{ $message->ip_address }}</span>
                            @endif
                        </div>
                    </div>
                </details>
            @empty
                <p class="px-5 py-12 text-center text-sm text-ink-mute">{{ $filter === 'unread' ? 'Tidak ada pesan unread.' : ($filter === 'read' ? 'Belum ada pesan yang sudah dibaca.' : 'Belum ada pesan masuk.') }}</p>
            @endforelse
        </div>
    </div>

    @if ($messages->hasPages())
        <div class="mt-4 border border-line bg-surface-1 px-5 py-3.5 shadow-sm">
            {{ $messages->onEachSide(1)->links() }}
        </div>
    @endif
@endsection
