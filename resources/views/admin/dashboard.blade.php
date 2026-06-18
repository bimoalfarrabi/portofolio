@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Mission Control')
@section('heading', 'Dashboard')

@section('content')
    {{-- Stats overview --}}
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Projects', 'count' => $projectCount, 'route' => 'admin.projects.index', 'desc' => 'Orbit items'],
                ['label' => 'Skills', 'count' => $skillCount, 'route' => 'admin.skills.index', 'desc' => 'Constellation nodes'],
                ['label' => 'Logs', 'count' => $logCount, 'route' => 'admin.logs.index', 'desc' => 'Transmissions'],
                ['label' => 'Stats', 'count' => $statCount, 'route' => 'admin.stats.index', 'desc' => 'Escape velocity'],
            ];
        @endphp

        @foreach ($cards as $card)
            <a href="{{ route($card['route']) }}" class="group relative overflow-hidden border border-line bg-surface-1 p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="absolute -right-4 -top-4 size-20 rounded-full bg-surface-2 transition-transform duration-300 group-hover:scale-150"></div>
                <div class="relative">
                    <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute">{{ $card['desc'] }}</p>
                    <p class="mt-3 text-3xl font-semibold tracking-[-0.04em]">{{ $card['count'] }}</p>
                    <p class="mt-1 text-sm font-medium text-ink-soft">{{ $card['label'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Quick actions --}}
    <div class="mt-8">
        <p class="mb-4 font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute">Quick Actions</p>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $actions = [
                    ['label' => 'Tambah Project', 'route' => 'admin.projects.create'],
                    ['label' => 'Tambah Skill', 'route' => 'admin.skills.create'],
                    ['label' => 'Tambah Log', 'route' => 'admin.logs.create'],
                    ['label' => 'Tambah Stat', 'route' => 'admin.stats.create'],
                    ['label' => 'Atur Collab', 'route' => 'admin.collab.edit'],
                    ['label' => 'Inbox Messages', 'route' => 'admin.messages.index'],
                ];
            @endphp

            @foreach ($actions as $action)
                <a href="{{ route($action['route']) }}" class="flex items-center gap-3 border border-line bg-surface-1 px-4 py-3 text-sm font-medium text-ink-soft transition-all duration-150 hover:border-ink hover:bg-surface-2 hover:text-ink">
                    <svg class="size-4 text-ink-mute" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ $action['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Recent activity --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        {{-- Recent projects --}}
        <div class="border border-line bg-surface-1 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-ink">Recent Projects</p>
                <a href="{{ route('admin.projects.index') }}" class="text-xs font-medium text-ink-mute transition-colors hover:text-ink-soft">View all</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentProjects as $project)
                    <div class="flex items-center justify-between rounded-none bg-surface-2 px-3.5 py-2.5">
                        <div class="flex items-center gap-3">
                            <span class="size-2 rounded-full {{ $project->type === 'closed' ? 'bg-amber-400' : 'bg-ink' }}"></span>
                            <span class="text-sm font-medium text-ink-soft">{{ $project->title }}</span>
                        </div>
                        <span class="font-mono text-[10px] uppercase tracking-wider text-ink-mute">{{ $project->type }}</span>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-ink-mute">Belum ada project.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent logs --}}
        <div class="border border-line bg-surface-1 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-ink">Recent Logs</p>
                <a href="{{ route('admin.logs.index') }}" class="text-xs font-medium text-ink-mute transition-colors hover:text-ink-soft">View all</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($recentLogs as $log)
                    <div class="flex items-center justify-between rounded-none bg-surface-2 px-3.5 py-2.5">
                        <span class="text-sm font-medium text-ink-soft">{{ $log->title }}</span>
                        <span class="font-mono text-[10px] text-ink-mute">{{ $log->logged_at?->format('d M') ?? '-' }}</span>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-ink-mute">Belum ada log.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
