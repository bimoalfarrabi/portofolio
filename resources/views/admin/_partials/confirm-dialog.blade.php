<dialog id="adminConfirmDialog" class="hidden w-full max-w-md border border-line bg-surface-1 p-0 shadow-[0_35px_120px_rgba(24,24,27,0.18)] backdrop:bg-ink/40 backdrop:backdrop-blur-sm open:flex open:flex-col m-auto" aria-labelledby="adminConfirmTitle" aria-describedby="adminConfirmMessage">
    <div class="flex items-start gap-4 p-6">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full border border-red-100 bg-red-50 text-red-600">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008m-6.928 3.5h13.84c1.54 0 2.502-1.667 1.732-3L13.732 4.5c-.77-1.333-2.694-1.333-3.464 0L3.34 17c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </span>
        <div class="flex-1">
            <h2 id="adminConfirmTitle" class="text-base font-semibold tracking-[-0.02em] text-ink">Konfirmasi</h2>
            <p id="adminConfirmMessage" class="mt-1.5 text-sm leading-relaxed text-ink-mute">Apakah Anda yakin?</p>
        </div>
    </div>
    <div class="flex items-center justify-end gap-2 border-t border-line bg-surface-2 px-6 py-3.5">
        <button type="button" data-confirm-cancel class="border border-line bg-surface-1 px-4 py-2 text-sm font-medium text-ink-soft transition-colors hover:bg-surface-2">Batal</button>
        <button type="button" data-confirm-accept class="rounded-full bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-red-700">Hapus</button>
    </div>
</dialog>

@push('scripts')
    <script>
        (function () {
            const dialog = document.getElementById('adminConfirmDialog');
            if (!dialog) return;

            const titleEl = dialog.querySelector('#adminConfirmTitle');
            const messageEl = dialog.querySelector('#adminConfirmMessage');
            const acceptBtn = dialog.querySelector('[data-confirm-accept]');
            const cancelBtn = dialog.querySelector('[data-confirm-cancel]');

            let pendingForm = null;

            function open(form) {
                if (!dialog.showModal) {
                    if (window.confirm(form.dataset.confirm || 'Apakah Anda yakin?')) {
                        form.dataset.confirmed = 'true';
                        form.requestSubmit();
                    }
                    return;
                }

                pendingForm = form;
                titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
                messageEl.textContent = form.dataset.confirm || 'Apakah Anda yakin?';
                acceptBtn.textContent = form.dataset.confirmAccept || 'Hapus';
                acceptBtn.className = form.dataset.confirmTone === 'neutral'
                    ? 'border border-ink bg-ink px-4 py-2 font-mono text-xs uppercase tracking-[0.18em] text-surface-1 transition-colors hover:bg-accent hover:border-accent'
                    : 'border border-red-600 bg-red-600 px-4 py-2 font-mono text-xs uppercase tracking-[0.18em] text-white transition-colors hover:bg-red-700 hover:border-red-700';
                dialog.classList.remove('hidden');
                dialog.showModal();
                requestAnimationFrame(() => acceptBtn.focus());
            }

            function close() {
                pendingForm = null;
                if (dialog.open) dialog.close();
                dialog.classList.add('hidden');
            }

            document.addEventListener('submit', function (event) {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) return;
                if (!form.dataset.confirm) return;
                if (form.dataset.confirmed === 'true') return;

                event.preventDefault();
                open(form);
            }, true);

            acceptBtn.addEventListener('click', function () {
                if (!pendingForm) return close();
                const form = pendingForm;
                close();
                form.dataset.confirmed = 'true';
                form.requestSubmit();
            });

            cancelBtn.addEventListener('click', close);

            dialog.addEventListener('cancel', function (event) {
                event.preventDefault();
                close();
            });

            dialog.addEventListener('click', function (event) {
                if (event.target === dialog) close();
            });
        })();

        (function () {
            const labelCache = new WeakMap();

            function setLoading(button) {
                if (button.dataset.loading === 'true') return;
                button.dataset.loading = 'true';
                labelCache.set(button, button.innerHTML);
                button.disabled = true;
                button.classList.add('opacity-70');
                button.setAttribute('aria-busy', 'true');

                const hasText = button.textContent.trim().length > 0;
                const text = button.dataset.loadingText || (hasText ? 'Memproses...' : '');
                const spinner =
                    '<svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">' +
                        '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>' +
                        '<path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>' +
                    '</svg>';
                button.innerHTML = text
                    ? '<span class="inline-flex items-center gap-2">' + spinner + '<span>' + text + '</span></span>'
                    : '<span class="inline-flex items-center justify-center">' + spinner + '</span>';
            }

            document.addEventListener('submit', function (event) {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) return;
                if (form.dataset.confirm && form.dataset.confirmed !== 'true') return;
                if (form.dataset.noLoading === 'true') return;

                const submitter = event.submitter;
                if (submitter && submitter.tagName === 'BUTTON' && submitter.type === 'submit') {
                    setLoading(submitter);
                }
            });

            window.addEventListener('pageshow', function (event) {
                if (!event.persisted) return;
                document.querySelectorAll('button[data-loading="true"]').forEach(function (button) {
                    const original = labelCache.get(button);
                    if (original !== undefined) button.innerHTML = original;
                    button.disabled = false;
                    button.removeAttribute('aria-busy');
                    button.classList.remove('opacity-70');
                    delete button.dataset.loading;
                });
                document.querySelectorAll('form[data-confirmed="true"]').forEach(function (form) {
                    delete form.dataset.confirmed;
                });
            });
        })();
    </script>
@endpush
