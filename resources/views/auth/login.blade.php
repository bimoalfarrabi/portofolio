<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Mission Control</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-surface-0 text-ink antialiased">
    <main class="flex min-h-screen items-center justify-center px-5 py-12">
        {{-- Ambient glow --}}
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
                    </div>

        <div class="relative w-full max-w-md">
            {{-- Brand --}}
            <div class="mb-8 flex flex-col items-center gap-3">
                <span class="flex size-12 items-center justify-center border border-line bg-surface-1 shadow-sm">
                    <span class="flex size-6 items-center justify-center rounded-full bg-ink">
                        <span class="size-1.5 rounded-full bg-white"></span>
                    </span>
                </span>
                <div class="text-center">
                    <p class="text-lg font-semibold tracking-[-0.03em]">Mission Control</p>
                    <p class="font-mono text-[10px] uppercase tracking-[0.28em] text-ink-mute">Admin Access</p>
                </div>
            </div>

            {{-- Login card --}}
            <form method="POST" action="{{ route('admin.login.store') }}" class="rounded-none border border-line bg-surface-1 p-8 shadow-[0_35px_120px_rgba(24,24,27,0.06)] backdrop-blur-xl">
                @csrf

                <div class="space-y-5">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-ink-soft">Nama atau Email</span>
                        <input id="login" name="login" type="text" value="{{ old('login') }}" class="w-full border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors placeholder:text-ink-faint focus:border-ink-soft focus:ring-0" placeholder="Nama akun atau admin@example.com" autocomplete="username" required autofocus>
                        @error('login')
                            <p class="text-xs text-warn">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-ink-soft">Password</span>
                        <input id="password" name="password" type="password" class="w-full border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors placeholder:text-ink-faint focus:border-ink-soft focus:ring-0" placeholder="••••••••" autocomplete="current-password" required>
                    </label>

                    <label class="flex items-center gap-2.5 text-sm text-ink-soft">
                        <input type="checkbox" name="remember" class="size-4 rounded border-ink text-ink focus:ring-ink">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="mt-8 w-full border border-ink bg-ink px-4 py-3 font-mono text-xs uppercase tracking-[0.18em] text-surface-1 transition-colors hover:bg-accent hover:border-accent">
                    Login
                </button>
            </form>

            {{-- Footer --}}
            <p class="mt-6 text-center font-mono text-[10px] uppercase tracking-[0.2em] text-ink-faint">Secure access only</p>
        </div>
    </main>
</body>
</html>
