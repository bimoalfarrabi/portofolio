@extends('layouts.admin')

@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Skill')
@section('breadcrumb', 'CMS / Skills / ' . ($mode === 'create' ? 'Tambah' : 'Edit'))
@section('heading', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Skill')

@section('actions')
    <x-admin.back-button :href="route('admin.skills.index')" />
@endsection

@section('content')
    <form method="POST" action="{{ $mode === 'create' ? route('admin.skills.store') : route('admin.skills.update', $skill) }}" class="space-y-4">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <x-admin.panel label="Identity">
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Name <span class="text-accent">*</span></span>
                    <input id="skill-name" name="name" value="{{ old('name', $skill->name) }}" class="@error('name') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="e.g. React, Laravel">
                    <x-admin.field-error name="name" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Category</span>
                    <input name="category" value="{{ old('category', $skill->category) }}" class="@error('category') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="e.g. Frontend, Backend">
                    <x-admin.field-error name="category" />
                </label>
            </div>
        </x-admin.panel>

        <x-admin.panel label="Visual">
            <div class="grid gap-2">
                <span class="text-sm font-medium text-ink-soft">Icon</span>
                <div class="flex items-stretch gap-3">
                    <span
                        class="flex size-12 shrink-0 items-center justify-center border border-line bg-surface-2"
                        data-brand-icon-preview
                        data-brand-icon-value="{{ old('icon', $skill->icon ?? '') }}"
                        data-brand-icon-fallback="{{ old('name', $skill->name ?? '') }}"
                        data-brand-icon-source="skill-icon"
                        data-brand-icon-fallback-source="skill-name"
                        aria-hidden="true"
                    ></span>
                    <select id="skill-icon" name="icon" class="@error('icon') border-warn @enderror w-full border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0">
                        <option value="">Auto (deteksi dari nama skill)</option>
                        @foreach (config('skill_icons.groups', []) as $group => $keys)
                            <optgroup label="{{ $group }}">
                                @foreach ($keys as $key)
                                    <option value="{{ $key }}" @selected(old('icon', $skill->icon) === $key)>{{ ucfirst($key) }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <x-admin.field-error name="icon" />
                <span class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">
                    Kosongkan untuk deteksi otomatis dari nama. Preview di kiri update real-time.
                </span>
            </div>
        </x-admin.panel>

        <x-admin.panel label="Meta">
            <div class="grid gap-5 sm:grid-cols-[1fr_auto] sm:items-end">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Sort Order</span>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $skill->sort_order ?? 0) }}" class="@error('sort_order') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0">
                    <x-admin.field-error name="sort_order" />
                </label>
                <x-admin.checkbox-pill name="is_active" label="Active" :checked="old('is_active', $skill->is_active)" />
            </div>
        </x-admin.panel>

        <x-admin.submit-bar :cancel="route('admin.skills.index')" label="Simpan Skill" />
    </form>
@endsection
