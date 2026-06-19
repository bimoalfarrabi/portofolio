@props([])

<div {{ $attributes->merge(['class' => 'mb-5 flex gap-0 border-b border-line']) }} data-lang-tabs>
    <button type="button"
        data-lang-tab="id"
        class="px-4 py-2 font-mono text-[10px] uppercase tracking-[0.2em] transition-colors border-b-2 border-ink text-ink"
    >ID</button>
    <button type="button"
        data-lang-tab="en"
        class="px-4 py-2 font-mono text-[10px] uppercase tracking-[0.2em] transition-colors border-b-2 border-transparent text-ink-mute hover:text-ink-soft"
    >EN</button>
</div>

@once
@push('scripts')
<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-lang-tabs]').forEach(function (tabsEl) {
            // Find the closest ancestor panel or form to scope panels
            const scope = tabsEl.closest('[class*="border border-line"]') || tabsEl.closest('form') || document;

            function activate(lang) {
                // Update tab styles
                tabsEl.querySelectorAll('[data-lang-tab]').forEach(function (btn) {
                    const active = btn.dataset.langTab === lang;
                    btn.classList.toggle('border-ink', active);
                    btn.classList.toggle('text-ink', active);
                    btn.classList.toggle('border-transparent', !active);
                    btn.classList.toggle('text-ink-mute', !active);
                });

                // Show/hide panels scoped to this tabs container
                scope.querySelectorAll('[data-lang-panel]').forEach(function (panel) {
                    const show = panel.dataset.langPanel === lang;
                    panel.classList.toggle('hidden', !show);
                });
            }

            tabsEl.querySelectorAll('[data-lang-tab]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    activate(btn.dataset.langTab);
                });
            });

            // Default: show ID
            activate('id');
        });
    });
})();
</script>
@endpush
@endonce
