@extends('layouts.admin')

@section('title', 'Collab')
@section('breadcrumb', 'CMS / Collab')
@section('heading', 'Atur Collab')

@section('actions')
    <a href="{{ url('/') }}#collab" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border border-line bg-surface-1 px-4 py-2.5 text-sm font-medium text-ink-soft transition-colors duration-150 hover:border-ink hover:text-ink">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
        Lihat di web
    </a>
@endsection

@section('content')
    @php($channels = old('channels', $collab->channels ?: []))
    <form method="POST" action="{{ route('admin.collab.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Kontak --}}
        <x-admin.panel label="Kontak">
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Email <span class="text-accent">*</span></span>
                    <input type="email" name="email" value="{{ old('email', $collab->email) }}" class="@error('email') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="nama@email.com">
                    <x-admin.field-error name="email" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Response Time</span>
                    <input name="response_time" value="{{ old('response_time', $collab->response_time) }}" class="@error('response_time') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="e.g. Usually replies within 24h">
                    <x-admin.field-error name="response_time" />
                </label>
            </div>
        </x-admin.panel>

        {{-- Availability --}}
        <x-admin.panel label="Availability">
            <x-admin.checkbox-pill name="available" label="Available — terbuka untuk project baru" :checked="old('available', $collab->available)" />
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Label saat available <span class="text-accent">*</span></span>
                    <input name="available_label" value="{{ old('available_label', $collab->available_label) }}" class="@error('available_label') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="Available for new projects">
                    <x-admin.field-error name="available_label" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Label saat busy <span class="text-accent">*</span></span>
                    <input name="busy_label" value="{{ old('busy_label', $collab->busy_label) }}" class="@error('busy_label') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="Booked, but still reading messages">
                    <x-admin.field-error name="busy_label" />
                </label>
            </div>
        </x-admin.panel>

        {{-- Lokasi --}}
        <x-admin.panel label="Lokasi & Waktu">
            <div class="grid gap-5 sm:grid-cols-3">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Based in</span>
                    <input name="location" value="{{ old('location', $collab->location) }}" class="@error('location') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Indonesia">
                    <x-admin.field-error name="location" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Time Zone <span class="text-accent">*</span></span>
                    <input name="time_zone" value="{{ old('time_zone', $collab->time_zone) }}" list="tz-list" class="@error('time_zone') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="Asia/Jakarta">
                    <datalist id="tz-list">
                        @foreach (timezone_identifiers_list() as $tz)
                            <option value="{{ $tz }}"></option>
                        @endforeach
                    </datalist>
                    <x-admin.field-error name="time_zone" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Time Zone Label</span>
                    <input name="time_zone_label" value="{{ old('time_zone_label', $collab->time_zone_label) }}" class="@error('time_zone_label') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="GMT+7">
                    <x-admin.field-error name="time_zone_label" />
                </label>
            </div>
        </x-admin.panel>

        {{-- Channels --}}
        <x-admin.panel label="Channels">
            <div class="-mt-7 mb-5 flex justify-end">
                <button type="button" data-channel-add class="inline-flex items-center gap-1.5 border border-line bg-surface-1 px-3 py-1.5 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft transition-colors hover:border-ink hover:text-ink">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Channel
                </button>
            </div>
            <x-admin.field-error name="channels" />

            <div data-channel-list class="space-y-3">
                @forelse ($channels as $i => $channel)
                    <div data-channel-row class="grid gap-3 rounded-none border border-line bg-surface-2 p-4 sm:grid-cols-[1fr_1.5fr_1fr_auto] sm:items-end">
                        <label class="grid gap-1.5">
                            <span class="text-xs font-medium text-ink-soft">Label</span>
                            <input name="channels[{{ $i }}][label]" value="{{ $channel['label'] ?? '' }}" class="border border-line bg-surface-1 px-3 py-2 text-sm outline-none focus:border-ink-soft" placeholder="LinkedIn">
                        </label>
                        <label class="grid gap-1.5">
                            <span class="text-xs font-medium text-ink-soft">URL</span>
                            <input name="channels[{{ $i }}][href]" value="{{ $channel['href'] ?? '' }}" class="border border-line bg-surface-1 px-3 py-2 text-sm outline-none focus:border-ink-soft" placeholder="https://...">
                        </label>
                        <label class="grid gap-1.5">
                            <span class="text-xs font-medium text-ink-soft">Handle</span>
                            <input name="channels[{{ $i }}][handle]" value="{{ $channel['handle'] ?? '' }}" class="border border-line bg-surface-1 px-3 py-2 text-sm outline-none focus:border-ink-soft" placeholder="@username">
                        </label>
                        <button type="button" data-channel-remove class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-red-50 hover:text-red-600" title="Hapus">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
                @empty
                @endforelse
            </div>

            <template data-channel-template>
                <div data-channel-row class="grid gap-3 rounded-none border border-line bg-surface-2 p-4 sm:grid-cols-[1fr_1.5fr_1fr_auto] sm:items-end">
                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-ink-soft">Label</span>
                        <input name="channels[__INDEX__][label]" class="border border-line bg-surface-1 px-3 py-2 text-sm outline-none focus:border-ink-soft" placeholder="LinkedIn">
                    </label>
                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-ink-soft">URL</span>
                        <input name="channels[__INDEX__][href]" class="border border-line bg-surface-1 px-3 py-2 text-sm outline-none focus:border-ink-soft" placeholder="https://...">
                    </label>
                    <label class="grid gap-1.5">
                        <span class="text-xs font-medium text-ink-soft">Handle</span>
                        <input name="channels[__INDEX__][handle]" class="border border-line bg-surface-1 px-3 py-2 text-sm outline-none focus:border-ink-soft" placeholder="@username">
                    </label>
                    <button type="button" data-channel-remove class="rounded-lg p-2 text-ink-mute transition-colors hover:bg-red-50 hover:text-red-600" title="Hapus">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                </div>
            </template>
        </x-admin.panel>

        <x-admin.submit-bar :cancel="route('admin.dashboard')" label="Simpan Collab" />
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            const list = document.querySelector('[data-channel-list]');
            const template = document.querySelector('[data-channel-template]');
            const addBtn = document.querySelector('[data-channel-add]');
            if (!list || !template || !addBtn) return;

            let index = {{ count($channels) }};

            addBtn.addEventListener('click', function () {
                const html = template.innerHTML.replace(/__INDEX__/g, index);
                index += 1;
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html.trim();
                list.appendChild(wrapper.firstElementChild);
            });

            list.addEventListener('click', function (event) {
                const removeBtn = event.target.closest('[data-channel-remove]');
                if (!removeBtn) return;
                const row = removeBtn.closest('[data-channel-row]');
                row?.remove();
            });
        })();
    </script>
@endpush
