<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CMS') — Mission Control</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <script type="module" src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8/dist/turbo.es2017.esm.js"></script>
</head>
<body class="bg-surface-0 text-ink antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-line bg-surface-1 backdrop-blur-xl lg:flex">
            {{-- Brand --}}
            <div class="flex h-16 items-center gap-3 border-b border-line px-6">
                <span class="flex size-8 items-center justify-center rounded-full bg-ink">
                    <span class="size-2 rounded-full bg-white"></span>
                </span>
                <div>
                    <p class="text-sm font-semibold tracking-[-0.02em]">Mission Control</p>
                    <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-ink-mute">CMS</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 px-3 py-4">
                <p class="mb-2 px-3 font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute">Navigation</p>

                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'admin.projects.index', 'label' => 'Projects', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['route' => 'admin.skills.index', 'label' => 'Skills', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ['route' => 'admin.logs.index', 'label' => 'Logs', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route' => 'admin.stats.index', 'label' => 'Stats', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ['route' => 'admin.collab.edit', 'label' => 'Collab', 'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                        ['route' => 'admin.messages.index', 'label' => 'Messages', 'icon' => 'M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z'],
                        ['route' => 'admin.users.index', 'label' => 'Users', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php($isActive = request()->routeIs($item['route'] . '*') || request()->routeIs($item['route']))
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 border-l-2 px-3 py-2.5 text-sm font-medium transition-colors duration-150 {{ $isActive ? 'border-accent bg-surface-2 text-ink' : 'border-transparent text-ink-mute hover:bg-surface-2 hover:text-ink' }}">
                        <svg class="size-[18px] shrink-0 {{ $isActive ? 'text-accent' : 'text-ink-mute group-hover:text-ink-soft' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        <span class="flex-1">{{ $item['label'] }}</span>
                        @if ($item['route'] === 'admin.messages.index')
                            <span data-unread-badge
                                  class="inline-flex min-w-[1.25rem] items-center justify-center rounded-full px-1.5 py-0.5 text-[10px] font-semibold bg-accent text-surface-1 {{ ($unreadMessages ?? 0) > 0 ? '' : 'hidden' }}">
                                {{ $unreadMessages > 99 ? '99+' : $unreadMessages }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>

            {{-- Footer --}}
            <div class="border-t border-line px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-8 items-center justify-center rounded-full border border-line bg-surface-2 text-xs font-medium text-ink-soft">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </span>
                        <span class="text-sm font-medium text-ink-soft">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg p-1.5 text-ink-mute transition-colors hover:bg-surface-2 hover:text-ink-soft" title="Logout">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Mobile header --}}
        <header class="fixed inset-x-0 top-0 z-30 flex h-14 items-center justify-between border-b border-line bg-surface-1 px-4 backdrop-blur-xl lg:hidden">
            <div class="flex items-center gap-2.5">
                <span class="flex size-7 items-center justify-center rounded-full bg-ink">
                    <span class="size-1.5 rounded-full bg-white"></span>
                </span>
                <p class="text-sm font-semibold tracking-[-0.02em]">Mission Control</p>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="rounded-full border border-line px-3 py-1.5 text-xs font-medium text-ink-soft">Logout</button>
            </form>
        </header>

        {{-- Mobile nav --}}
        <nav class="fixed inset-x-0 bottom-0 z-30 flex items-center justify-around border-t border-line bg-surface-1 px-2 py-2 backdrop-blur-xl lg:hidden">
            @foreach ($navItems as $item)
                @php($isActive = request()->routeIs($item['route'] . '*') || request()->routeIs($item['route']))
                <a href="{{ route($item['route']) }}"
                   class="relative flex flex-col items-center gap-0.5 rounded-lg px-3 py-1.5 {{ $isActive ? 'text-ink' : 'text-ink-mute' }}">
                    <span class="relative">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        @if ($item['route'] === 'admin.messages.index')
                            <span data-unread-badge
                                  class="absolute -right-1.5 -top-1.5 inline-flex min-w-[1rem] items-center justify-center rounded-full bg-ink px-1 py-0.5 text-[9px] font-semibold text-white {{ ($unreadMessages ?? 0) > 0 ? '' : 'hidden' }}">
                                {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                            </span>
                        @endif
                    </span>
                    <span class="text-[10px] font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Main content --}}
        <main class="w-full pt-14 lg:pl-64 lg:pt-0">
            <div class="mx-auto max-w-6xl px-5 py-8 pb-24 lg:px-8 lg:py-10">
                {{-- Page header --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-mono text-[10px] uppercase tracking-[0.28em] text-ink-mute">@yield('breadcrumb', 'CMS')</p>
                        <h1 class="mt-1.5 text-2xl font-semibold tracking-[-0.04em] lg:text-3xl">@yield('heading')</h1>
                    </div>
                    @hasSection('actions')
                        <div class="flex items-center gap-2.5">
                            @yield('actions')
                        </div>
                    @endif
                </div>

                {{-- Flash messages --}}
                @if (session('status'))
                    <div class="mt-6 flex items-center gap-3 rounded-none border border-emerald-200/80 bg-emerald-50/80 px-5 py-3.5 text-sm text-emerald-700 backdrop-blur">
                        <svg class="size-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="mt-6 rounded-none border border-warn/40 bg-warn-soft/30 px-5 py-4 backdrop-blur">
                        <p class="text-sm font-medium text-warn-deep">Terdapat kesalahan:</p>
                        <ul class="mt-2 space-y-1 text-sm text-warn-deep">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start gap-2">
                                    <span class="mt-1.5 size-1 shrink-0 rounded-full bg-warn"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Page content --}}
                <div class="mt-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    @include('admin._partials.confirm-dialog')
    @stack('scripts')
    {{-- NASA cursor (vanilla JS — React not mounted in admin) --}}
    <div id="_nc-dot" aria-hidden="true" class="nasa-cursor-dot"></div>
    <div id="_nc-ring" aria-hidden="true" class="nasa-cursor-ring"></div>
    <script>
        (function () {
            if (window.matchMedia('(pointer: coarse)').matches) return;
            var dot = document.getElementById('_nc-dot');
            var ring = document.getElementById('_nc-ring');
            var mx = -100, my = -100, rx = -100, ry = -100;
            document.addEventListener('mousemove', function (e) {
                mx = e.clientX; my = e.clientY;
                var el = e.target.closest('a, button, [role="button"], input, textarea, select, label, [data-cursor="pointer"]');
                ring.dataset.active = el ? '1' : '0';
            });
            (function loop() {
                rx += (mx - rx) * 0.18;
                ry += (my - ry) * 0.18;
                dot.style.transform = 'translate(' + mx + 'px,' + my + 'px)';
                ring.style.transform = 'translate(' + rx + 'px,' + ry + 'px)';
                requestAnimationFrame(loop);
            })();
        })();
    </script>
    <script>
        document.addEventListener('turbo:load', function () {
        (function () {
            const badges = document.querySelectorAll('[data-unread-badge]');
            if (! badges.length) return;
            const endpoint = @json(route('admin.messages.unread-count'));

            function render(count) {
                badges.forEach((badge) => {
                    if (count > 0) {
                        const isMobile = badge.classList.contains('absolute');
                        badge.textContent = isMobile ? (count > 9 ? '9+' : count) : (count > 99 ? '99+' : count);
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });
            }

            async function poll() {
                try {
                    const res = await fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (! res.ok) return;
                    const data = await res.json();
                    render(Number(data.unread ?? 0));
                } catch (e) {
                    // diam saja; coba lagi pada interval berikutnya
                }
            }

            let timer = window.setInterval(poll, 30000);
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    window.clearInterval(timer);
                } else {
                    poll();
                    timer = window.setInterval(poll, 30000);
                }
            });
        })();
        }); // turbo:load
    </script>
</body>
</html>
