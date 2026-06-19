@extends('layouts.admin')

@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Log')
@section('breadcrumb', 'CMS / Logs / ' . ($mode === 'create' ? 'Tambah' : 'Edit'))
@section('heading', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Log')

@section('actions')
    <x-admin.back-button :href="route('admin.logs.index')" />
@endsection

@section('content')
    <form method="POST" action="{{ $mode === 'create' ? route('admin.logs.store') : route('admin.logs.update', $log) }}" class="space-y-4">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <x-admin.panel label="Detail Log">
            <div class="grid gap-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-ink-soft">Date</span>
                        <input type="date" name="logged_at" value="{{ old('logged_at', optional($log->logged_at)->format('Y-m-d')) }}" class="@error('logged_at') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0">
                        <x-admin.field-error name="logged_at" />
                    </label>
                    <label class="grid gap-2">
                        <span class="text-sm font-medium text-ink-soft">Sort Order</span>
                        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $log->sort_order ?? 0) }}" class="@error('sort_order') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0">
                        <x-admin.field-error name="sort_order" />
                    </label>
                </div>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Tags</span>
                    <input name="tags" value="{{ old('tags', is_array($log->tags) ? implode(', ', $log->tags) : '') }}" class="@error('tags') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="update, feature, fix (comma separated)">
                    <x-admin.field-error name="tags" />
                    <span class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">Pisahkan dengan koma</span>
                </label>
                <x-admin.checkbox-pill name="is_published" label="Published" :checked="old('is_published', $log->is_published)" />
            </div>
        </x-admin.panel>

        {{-- Konten bilingual --}}
        <x-admin.panel label="Konten">
            <x-admin.lang-tabs />

            {{-- ID --}}
            <div data-lang-panel="id" class="grid gap-5">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Title (ID) <span class="text-accent">*</span></span>
                    <input name="title" value="{{ old('title', $log->title) }}" class="@error('title') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="Judul log entry">
                    <x-admin.field-error name="title" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Body (ID)</span>
                    <textarea name="body" rows="6" class="@error('body') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm leading-relaxed outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Isi log...">{{ old('body', $log->body) }}</textarea>
                    <x-admin.field-error name="body" />
                </label>
            </div>

            {{-- EN --}}
            <div data-lang-panel="en" class="hidden grid gap-5">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Title (EN)</span>
                    <input name="title_en" value="{{ old('title_en', $log->title_en) }}" class="@error('title_en') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Log entry title">
                    <x-admin.field-error name="title_en" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Body (EN)</span>
                    <textarea name="body_en" rows="6" class="@error('body_en') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm leading-relaxed outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Log content...">{{ old('body_en', $log->body_en) }}</textarea>
                    <x-admin.field-error name="body_en" />
                </label>
            </div>
        </x-admin.panel>

        <x-admin.submit-bar :cancel="route('admin.logs.index')" label="Simpan Log" />
    </form>
@endsection
