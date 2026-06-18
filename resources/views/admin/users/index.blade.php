@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute">CMS / Users</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-ink">Manajemen user</h1>
                <p class="mt-2 text-sm text-ink-mute">Kelola akun admin dan pengguna CMS.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 self-start border border-ink bg-ink px-4 py-2.5 font-mono text-xs uppercase tracking-[0.18em] text-surface-1 transition-colors hover:bg-accent hover:border-accent">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah User
            </a>
        </div>

        <form method="GET" class="flex gap-3 border border-line bg-surface-1 p-4 shadow-sm">
            <input name="q" value="{{ request('q') }}" class="min-w-0 flex-1 border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Cari nama atau email...">
            <button type="submit" class="border border-ink bg-ink px-5 py-3 font-mono text-xs uppercase tracking-[0.18em] text-surface-1 transition-colors hover:bg-accent hover:border-accent">Cari</button>
            @if (request('q'))
                <a href="{{ route('admin.users.index') }}" class="border border-line bg-surface-1 px-5 py-3 font-mono text-xs uppercase tracking-[0.18em] text-ink-soft transition-colors hover:border-ink hover:text-ink">Reset</a>
            @endif
        </form>


        <div class="overflow-hidden rounded-none border border-line bg-white shadow-sm">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-surface-2">
                    <tr class="text-left text-[11px] font-semibold uppercase tracking-[0.18em] text-ink-mute">
                        <th class="px-5 py-4">User</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4">Role</th>
                        <th class="px-5 py-4">Created</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($users as $user)
                        <tr class="group transition-colors hover:bg-surface-2">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-9 items-center justify-center rounded-full border border-line bg-surface-2 text-xs font-semibold text-ink-soft">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="font-medium text-ink">{{ $user->name }}</p>
                                        <p class="text-xs text-ink-mute">ID {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-ink-mute">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @if ($user->is_admin)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                        <span class="size-1.5 rounded-full bg-indigo-500"></span>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-surface-2 px-2.5 py-0.5 text-xs font-medium text-ink-mute">
                                        <span class="size-1.5 rounded-full bg-line"></span>
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-ink-mute">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1 transition-opacity sm:opacity-60 sm:group-hover:opacity-100 sm:focus-within:opacity-100">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-surface-2 hover:text-ink-soft" title="Edit">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    @if (! auth()->id() || auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-indigo-50 hover:text-indigo-600" title="Toggle admin">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m9 0h.75A2.25 2.25 0 0119.5 12v7.5A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75V12a2.25 2.25 0 012.25-2.25h.75m9 0h-9" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm="User akan dihapus permanen. Tindakan ini tidak dapat dibatalkan." data-confirm-title="Hapus User">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-red-50 hover:text-red-600" title="Delete">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="rounded-lg px-3 py-2 text-xs font-medium text-ink-mute">Aktif</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                    <x-admin.empty-state
                        :colspan="5"
                        message="Belum ada user."
                        :action="route('admin.users.create')"
                        action-label="Tambah user pertama"
                        icon="M17 20h5v-2a4 4 0 00-5-3.874M9 20H4v-2a4 4 0 015-3.874m0 0a4 4 0 117 0m-7 0a4 4 0 107 0M9 7a4 4 0 118 0 4 4 0 01-8 0z"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-line bg-surface-2 px-5 py-3.5">
                {{ $users->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
@endsection
