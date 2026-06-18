@extends('layouts.admin')

@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Project')
@section('breadcrumb', 'CMS / Projects / ' . ($mode === 'create' ? 'Tambah' : 'Edit'))
@section('heading', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Project')

@section('actions')
    <x-admin.back-button :href="route('admin.projects.index')" />
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ $mode === 'create' ? route('admin.projects.store') : route('admin.projects.update', $project) }}" class="space-y-4">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        @php($stackValue = is_array($project->stack) ? implode(', ', $project->stack) : '')

        {{-- Basic info --}}
        <x-admin.panel label="Informasi Dasar">
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Title <span class="text-accent">*</span></span>
                    <input name="title" value="{{ old('title', $project->title) }}" class="@error('title') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" required placeholder="Nama project">
                    <x-admin.field-error name="title" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Type</span>
                    <select name="type" id="field-type" class="@error('type') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0">
                        <option value="open" @selected(old('type', $project->type ?: 'open') === 'open')>Open Source</option>
                        <option value="closed" @selected(old('type', $project->type) === 'closed')>Closed Source</option>
                    </select>
                    <x-admin.field-error name="type" />
                </label>
                <div id="repo-url-field" class="grid gap-2 {{ old('type', $project->type ?: 'open') === 'closed' ? 'hidden' : '' }}">
                    <span class="text-sm font-medium text-ink-soft">Repository URL</span>
                    <input name="repo_url" value="{{ old('repo_url', $project->repo_url) }}" class="@error('repo_url') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="https://github.com/username/repo">
                    <x-admin.field-error name="repo_url" />
                    <span class="text-xs text-ink-mute">Link repository publik. Ditampilkan di halaman publik sebagai tombol.</span>
                </div>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Category</span>
                    <input name="category" value="{{ old('category', $project->category) }}" class="@error('category') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="e.g. Web App, CLI Tool">
                    <x-admin.field-error name="category" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Year</span>
                    <input name="year" value="{{ old('year', $project->year) }}" class="@error('year') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="2024">
                    <x-admin.field-error name="year" />
                </label>
            </div>
        </x-admin.panel>

        {{-- Content --}}
        <x-admin.panel label="Konten">
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Cover Image</span>
                    @php($currentImage = $project->image_url)
                    @if ($currentImage)
                        <div class="flex items-center gap-4 rounded-none border border-line bg-surface-2 p-3">
                            <img src="{{ $currentImage }}" alt="Preview" class="h-20 w-32 shrink-0 rounded-lg border border-line object-cover">
                            <div class="flex-1 space-y-1.5">
                                <p class="text-xs font-medium text-ink-soft">Gambar saat ini</p>
                                <p class="break-all font-mono text-[11px] text-ink-mute">{{ \Illuminate\Support\Str::limit($project->image, 80) }}</p>
                                <label class="inline-flex items-center gap-2 text-xs text-ink-mute">
                                    <input type="checkbox" name="remove_image" value="1" class="size-3.5 rounded border-ink text-red-500 focus:ring-red-400">
                                    <span>Hapus gambar saat menyimpan</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <label class="grid gap-2">
                        <span class="text-xs font-medium uppercase tracking-[0.2em] text-ink-mute">Upload File</span>
                        <input type="file" name="image_file" accept="image/png,image/jpeg,image/webp,image/gif" data-image-input class="block w-full cursor-pointer rounded-none border border-dashed border-ink bg-surface-1 px-4 py-3 text-sm text-ink-soft file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-ink file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-white hover:border-ink-soft">
                        <span class="text-xs text-ink-mute">PNG, JPG, WEBP, atau GIF. Maks 4 MB.</span>
                    </label>

                    <div data-image-preview class="hidden overflow-hidden rounded-none border border-line bg-surface-2 p-3">
                        <p class="mb-2 text-xs font-medium text-ink-soft">Preview unggahan</p>
                        <img alt="Preview unggahan" class="h-32 w-full rounded-lg border border-line object-cover" />
                    </div>

                    <label class="grid gap-2">
                        <span class="text-xs font-medium uppercase tracking-[0.2em] text-ink-mute">Atau Image URL</span>
                        <input name="image" value="{{ old('image', $project->image && ! \Illuminate\Support\Str::startsWith($project->image, ['http://', 'https://', '//', 'data:']) ? '' : $project->image) }}" class="@error('image') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="https://...">
                        <x-admin.field-error name="image" />
                        <span class="text-xs text-ink-mute">Diabaikan jika file diupload. Cocok untuk asset eksternal.</span>
                    </label>
                </div>

                <div class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Galeri Gambar</span>
                    <span class="text-xs text-ink-mute">Jika lebih dari satu gambar, modal di halaman publik otomatis menampilkan slider. Cover di atas selalu jadi gambar pertama.</span>

                    @php($galleryUrls = $project->gallery_urls ?? [])
                    @php($galleryRaw = is_array($project->gallery) ? $project->gallery : [])
                    @if (count($galleryUrls))
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                            @foreach ($galleryUrls as $idx => $gUrl)
                                <div class="group relative overflow-hidden rounded-none border border-line bg-surface-2">
                                    <img src="{{ $gUrl }}" alt="Galeri {{ $idx + 1 }}" loading="lazy" class="aspect-[4/3] w-full object-cover">
                                    <label class="absolute inset-x-0 bottom-0 flex items-center gap-2 bg-surface-1 px-2.5 py-1.5 text-xs text-ink-soft backdrop-blur">
                                        <input type="checkbox" name="remove_gallery[]" value="{{ $galleryRaw[$idx] ?? '' }}" class="size-3.5 rounded border-ink text-red-500 focus:ring-red-400">
                                        <span>Hapus</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label class="grid gap-2">
                        <span class="text-xs font-medium uppercase tracking-[0.2em] text-ink-mute">Tambah Gambar (bisa pilih banyak)</span>
                        <input type="file" name="gallery_files[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple data-gallery-input class="block w-full cursor-pointer rounded-none border border-dashed border-ink bg-surface-1 px-4 py-3 text-sm text-ink-soft file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-ink file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-white hover:border-ink-soft">
                        <span class="text-xs text-ink-mute">PNG, JPG, WEBP, atau GIF. Maks 4 MB per file, hingga 12 file.</span>
                    </label>
                    <x-admin.field-error name="gallery_files" />

                    <div data-gallery-preview class="hidden grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"></div>
                </div>

                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Description</span>
                    <textarea name="description" rows="3" class="@error('description') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm leading-relaxed outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Deskripsi singkat project...">{{ old('description', $project->description) }}</textarea>
                    <x-admin.field-error name="description" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Approach</span>
                    <textarea name="approach" rows="3" class="@error('approach') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm leading-relaxed outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Pendekatan yang digunakan...">{{ old('approach', $project->approach) }}</textarea>
                    <x-admin.field-error name="approach" />
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Stack</span>
                    <input name="stack" value="{{ old('stack', $stackValue) }}" class="@error('stack') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Laravel, React, Tailwind (comma separated)">
                    <x-admin.field-error name="stack" />
                    <span class="text-xs text-ink-mute">Pisahkan dengan koma</span>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-medium text-ink-soft">Outcome</span>
                    <textarea name="outcome" rows="3" class="@error('outcome') border-warn @enderror border border-line bg-surface-1 px-4 py-3 text-sm leading-relaxed outline-none transition-colors focus:border-ink-soft focus:ring-0" placeholder="Hasil yang dicapai...">{{ old('outcome', $project->outcome) }}</textarea>
                    <x-admin.field-error name="outcome" />
                </label>
            </div>
        </x-admin.panel>

        {{-- Settings --}}
        <x-admin.panel label="Pengaturan">
            <div class="grid gap-6">

                {{-- Sort Order --}}
                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-ink-soft">Sort Order</span>
                        <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-ink-mute">Urutan tampil di orbit</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" class="@error('sort_order') border-warn @enderror w-28 shrink-0 border border-line bg-surface-1 px-4 py-3 text-sm outline-none transition-colors focus:border-ink-soft focus:ring-0">
                        <p class="pt-2 text-xs leading-6 text-ink-mute">
                            Angka lebih kecil tampil lebih dulu dalam orbit.<br>
                            <span class="text-ink-faint">Contoh: <code class="bg-surface-2 px-1 py-0.5 font-mono">0</code> = paling pertama &middot; <code class="bg-surface-2 px-1 py-0.5 font-mono">10</code> = setelah yang bernilai 0&ndash;9. Project dengan nilai sama diurutkan by ID.</span>
                        </p>
                    </div>
                    <x-admin.field-error name="sort_order" />
                </div>

                {{-- Visibilitas & Featured --}}
                <div class="grid gap-3">
                    <span class="text-sm font-medium text-ink-soft">Visibilitas &amp; Tampilan Orbit</span>
                    <div class="flex flex-wrap items-start gap-3">
                        <x-admin.checkbox-pill name="is_published" label="Published" :checked="old('is_published', $project->is_published)" />

                        <label class="inline-flex cursor-pointer items-center gap-3 border border-accent/40 bg-accent/5 px-4 py-3 text-sm text-ink-soft transition-colors hover:border-accent hover:bg-accent/10">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured ?? false)) class="size-4 rounded-none border-ink text-ink focus:ring-ink">
                            <span class="font-mono text-xs uppercase tracking-[0.18em]">Featured</span>
                        </label>
                    </div>
                    <p class="text-xs leading-6 text-ink-mute">
                        <span class="font-medium text-ink-soft">Featured</span> &mdash; node project ini tampil lebih besar di orbit dengan dot accent.
                        Hanya satu project per orbit yang bisa featured sekaligus &mdash; mengaktifkan ini otomatis menonaktifkan featured project lain di orbit yang sama (open/closed).
                    </p>
                </div>

            </div>
        </x-admin.panel>

        <x-admin.submit-bar :cancel="route('admin.projects.index')" label="Simpan Project" />
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            const input = document.querySelector('[data-image-input]');
            const preview = document.querySelector('[data-image-preview]');
            if (!input || !preview) return;
            const img = preview.querySelector('img');

            input.addEventListener('change', function (event) {
                const file = event.target.files && event.target.files[0];
                if (!file) {
                    preview.classList.add('hidden');
                    img.removeAttribute('src');
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (loaded) {
                    img.src = loaded.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
        })();

        (function () {
            const input = document.querySelector('[data-gallery-input]');
            const preview = document.querySelector('[data-gallery-preview]');
            if (!input || !preview) return;

            input.addEventListener('change', function (event) {
                preview.innerHTML = '';
                const files = event.target.files ? Array.from(event.target.files) : [];
                if (files.length === 0) {
                    preview.classList.add('hidden');
                    preview.classList.remove('grid');
                    return;
                }
                preview.classList.remove('hidden');
                preview.classList.add('grid');
                files.forEach((file) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'overflow-hidden rounded-none border border-line bg-surface-2';
                    const img = document.createElement('img');
                    img.alt = file.name;
                    img.className = 'aspect-[4/3] w-full object-cover';
                    const reader = new FileReader();
                    reader.onload = function (loaded) {
                        img.src = loaded.target.result;
                    };
                    reader.readAsDataURL(file);
                    wrapper.appendChild(img);
                    preview.appendChild(wrapper);
                });
            });
        })();

        (function () {
            const typeSelect = document.getElementById('field-type');
            const repoField = document.getElementById('repo-url-field');
            if (!typeSelect || !repoField) return;

            typeSelect.addEventListener('change', function () {
                if (typeSelect.value === 'open') {
                    repoField.classList.remove('hidden');
                } else {
                    repoField.classList.add('hidden');
                }
            });
        })();
    </script>
@endpush
