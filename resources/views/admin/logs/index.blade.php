@extends('layouts.admin')

@section('title', 'Logs')
@section('breadcrumb', 'CMS / Logs')
@section('heading', 'Kelola Logs')

@section('actions')
    <a href="{{ route('admin.logs.create') }}" class="inline-flex items-center gap-2 border border-ink bg-ink px-4 py-2.5 font-mono text-xs uppercase tracking-[0.18em] text-surface-1 transition-colors hover:bg-accent hover:border-accent">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Log
    </a>
@endsection

@section('content')
    <div class="overflow-hidden border border-line bg-surface-1 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-surface-2">
                        <th class="px-5 py-3.5 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">Title</th>
                        <th class="px-5 py-3.5 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">Date</th>
                        <th class="px-5 py-3.5 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">Status</th>
                        <th class="px-5 py-3.5 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($logs as $log)
                        <tr class="group transition-colors hover:bg-surface-2">
                            <td class="px-5 py-4">
                                <span class="font-medium text-ink">{{ $log->title }}</span>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-ink-mute">{{ $log->logged_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @if ($log->is_published)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-surface-2 px-2.5 py-0.5 text-xs font-medium text-ink-mute">
                                        <span class="size-1.5 rounded-full bg-line"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1 transition-opacity sm:opacity-60 sm:group-hover:opacity-100 sm:focus-within:opacity-100">
                                    <a href="{{ route('admin.logs.edit', $log) }}" class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-surface-2 hover:text-ink-soft" title="Edit">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.logs.destroy', $log) }}" data-confirm="Log akan dihapus permanen. Tindakan ini tidak dapat dibatalkan." data-confirm-title="Hapus Log">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-red-50 hover:text-red-600" title="Delete">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <x-admin.empty-state
                        :colspan="4"
                        message="Belum ada log."
                        :action="route('admin.logs.create')"
                        action-label="Tambah log pertama"
                        icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="border-t border-line bg-surface-2 px-5 py-3.5">
                {{ $logs->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
@endsection
