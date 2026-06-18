@extends('layouts.admin')

@php($isEdit = $mode === 'edit')

@section('title', $isEdit ? 'Edit User' : 'Tambah User')
@section('breadcrumb', 'CMS / Users / ' . ($isEdit ? 'Edit' : 'Tambah'))
@section('heading', $isEdit ? 'Edit User' : 'Tambah User')

@section('actions')
    <x-admin.back-button :href="route('admin.users.index')" />
@endsection

@section('content')
    <form method="POST" action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-4">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <x-admin.panel label="Akun">
            <div class="grid gap-5 md:grid-cols-2">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Name</span>
                    <input name="name" value="{{ old('name', $user->name) }}" autocomplete="name" class="@error('name') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Nama user">
                    <x-admin.field-error name="name" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Email</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" class="@error('email') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="user@example.com">
                    <x-admin.field-error name="email" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Password</span>
                    <input type="password" name="password" autocomplete="new-password" class="@error('password') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}">
                    <x-admin.field-error name="password" />
                    @if ($isEdit)
                        <span class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">Kosongkan jika tidak perlu ganti password.</span>
                    @endif
                </label>
                <div class="flex items-end">
                    <x-admin.checkbox-pill name="is_admin" label="Admin access" :checked="old('is_admin', $user->is_admin)" />
                </div>
            </div>
        </x-admin.panel>

        <x-admin.submit-bar :cancel="route('admin.users.index')" :label="$isEdit ? 'Simpan User' : 'Buat User'" />
    </form>
@endsection
