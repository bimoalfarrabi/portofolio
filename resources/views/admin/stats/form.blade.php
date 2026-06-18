@extends('layouts.admin')

@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Stat')
@section('breadcrumb', 'CMS / Stats / ' . ($mode === 'create' ? 'Tambah' : 'Edit'))
@section('heading', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Stat')

@section('actions')
    <x-admin.back-button :href="route('admin.stats.index')" />
@endsection

@section('content')
    <form method="POST" action="{{ $mode === 'create' ? route('admin.stats.store') : route('admin.stats.update', $stat) }}" class="space-y-4">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <x-admin.panel label="Detail Stat">
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Key <span class="text-accent">*</span></span>
                    <input name="key" value="{{ old('key', $stat->key) }}" class="@error('key') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="e.g. projects_shipped">
                    <x-admin.field-error name="key" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Label <span class="text-accent">*</span></span>
                    <input name="label" value="{{ old('label', $stat->label) }}" class="@error('label') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="e.g. Projects Shipped">
                    <x-admin.field-error name="label" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Value <span class="text-accent">*</span></span>
                    <input name="value" value="{{ old('value', $stat->value) }}" class="@error('value') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="e.g. 12+">
                    <x-admin.field-error name="value" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Sort Order</span>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $stat->sort_order ?? 0) }}" class="@error('sort_order') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0">
                    <x-admin.field-error name="sort_order" />
                </label>
            </div>
            <label class="mt-5 grid gap-2">
                <span class="text-sm font-medium text-ink-soft">Note</span>
                <textarea name="note" rows="3" class="@error('note') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm leading-relaxed outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Catatan tambahan...">{{ old('note', $stat->note) }}</textarea>
                <x-admin.field-error name="note" />
            </label>
            <div class="mt-5">
                <x-admin.checkbox-pill name="is_active" label="Active" :checked="old('is_active', $stat->is_active)" />
            </div>
        </x-admin.panel>

        <x-admin.submit-bar :cancel="route('admin.stats.index')" label="Simpan Stat" />
    </form>
@endsection
