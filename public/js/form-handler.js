(function () {
    'use strict';

    function handleAjaxForm(form) {
        form.addEventListener('submit', async function (e) {
            
            e.preventDefault();
            e.stopImmediatePropagation();

            const loadingTitle = form.dataset.loading || null;
            const isMultipart  = form.enctype === 'multipart/form-data';
            const method       = (form.dataset.method || form.method || 'POST').toUpperCase();
            const action       = form.action;

            // Kumpulkan data form
            const body = isMultipart
                ? new FormData(form)
                : new URLSearchParams(new FormData(form));

            // Tampilkan loading jika diminta atau ada file upload
            if (loadingTitle || isMultipart) {
                SwalHelper.loading(
                    loadingTitle || 'Menyimpan data...',
                    'Mohon tunggu, jangan tutup halaman ini.'
                );
            }

            try {
                const response = await fetch(action, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN':    SwalHelper.csrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(isMultipart ? {} : { 'Content-Type': 'application/x-www-form-urlencoded' }),
                    },
                    body,
                });

                SwalHelper.close();

                // Redirect (HTML response)
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                const contentType = response.headers.get('content-type') || '';

                // JSON response 
                if (contentType.includes('application/json')) {
                    const data = await response.json();

                    if (!response.ok) {
                        await SwalHelper.handleError(
                            _buildFakeResponse(response.status, data),
                            'Permintaan Gagal'
                        );
                        return;
                    }

                    // Sukses: emit event supaya halaman bisa handle sendiri
                    form.dispatchEvent(new CustomEvent('ajax:success', {
                        bubbles: true,
                        detail:  { data, response },
                    }));

                    // Jika ada redirect_url di JSON
                    if (data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }

                    // Tampilkan pesan sukses jika ada
                    if (data.message) {
                        SwalHelper.toast('success', data.message);
                    }

                    return;
                }

                if (response.ok) {
                    // Ikuti URL terakhir 
                    window.location.href = response.url || action;
                    return;
                }

                // Error HTML (misal 500 page)  
                await SwalHelper.handleError(
                    _buildFakeResponse(response.status, { message: null }),
                    'Terjadi Kesalahan'
                );

            } catch (err) {
                SwalHelper.close();
                await SwalHelper.handleError(err);
            }
        });
    }

    function _buildFakeResponse(status, data) {
        return { response: { status, data } };
    }

    function bindConfirmDelete(root) {
        root.querySelectorAll('[data-confirm-delete]').forEach(el => {
            if (el._confirmDeleteBound) return; // hindari duplikat
            el._confirmDeleteBound = true;

            el.addEventListener('click', function (e) {
                e.preventDefault();

                const title   = this.dataset.title || 'Hapus data ini?';
                const text    = this.dataset.text  || 'Data tidak bisa dikembalikan setelah dihapus.';
                const formSel = this.dataset.form;
                const form    = formSel
                    ? document.querySelector(formSel)
                    : this.closest('form');

                SwalHelper.confirmDelete(title, text, () => {
                    if (form) {
                        form.submit();
                    } else {
                        console.warn('[form-handler] data-confirm-delete: form tidak ditemukan.');
                    }
                });
            });
        });
    }

    
    // Konfirmasi data                              
    
    function bindConfirm(root) {
        root.querySelectorAll('[data-confirm]').forEach(el => {
            if (el._confirmBound) return;
            el._confirmBound = true;

            el.addEventListener('click', function (e) {
                e.preventDefault();

                const title        = this.dataset.title       || 'Konfirmasi';
                const text         = this.dataset.text        || 'Apakah kamu yakin?';
                const confirmText  = this.dataset.confirmText || 'Ya, lanjutkan';
                const cancelText   = this.dataset.cancelText  || 'Batal';
                const formSel      = this.dataset.form;
                const form         = formSel
                    ? document.querySelector(formSel)
                    : this.closest('form');
                const href         = this.href || null;

                SwalHelper.confirm(title, text, () => {
                    if (form)       form.submit();
                    else if (href)  window.location.href = href;
                }, null, { confirmText, cancelText });
            });
        });
    }

    
    // error wilayah via SwalHelper           
    
    function patchWilayahErrorHandler() {
        if (typeof window.fetchAndPopulateTS !== 'function') return;

        const _original = window.fetchAndPopulateTS;

        window.fetchAndPopulateTS = function (url, code, id, placeholder, selectValue, callback) {

            const TS = window.TS; // objek TomSelect global dari _scripts
            if (!TS || !TS[id]) {
                _original(url, code, id, placeholder, selectValue, callback);
                return;
            }

            TS[id].clear(true);
            TS[id].clearOptions();
            TS[id].disable();

            fetch(url.replace(':code', code))
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(data => {
                    if (!TS[id]) return;
                    data.forEach(item => TS[id].addOption({ value: item.code, text: item.name }));
                    TS[id].refreshOptions(false);
                    TS[id].enable();
                    if (selectValue) TS[id].setValue(selectValue, true);
                    if (callback) callback();
                })
                .catch(err => {
                    if (!TS[id]) return;
                    TS[id].enable();

                    // Cek jenis error
                    if (!navigator.onLine || err instanceof TypeError) {
                        SwalHelper.toast('error', 'Gagal memuat wilayah: periksa koneksi internet.');
                    } else {
                        SwalHelper.toast('error', 'Gagal memuat data wilayah. Coba lagi.');
                    }

                    console.warn('[form-handler] fetchAndPopulateTS error:', err);
                });
        };
    }

    function handleBladeErrors() {
        const el = document.getElementById('blade-errors');
        if (!el) return;

        let messages = [];
        try {
            messages = JSON.parse(el.dataset.errors || '[]');
        } catch (_) { return; }

        if (!messages.length) return;

        const html = '<ul style="text-align:left;margin:0;padding-left:1.2rem">'
            + messages.map(m => `<li>${m}</li>`).join('')
            + '</ul>';

        // Tunda sedikit agar DOM & SwalHelper siap
        setTimeout(() => {
            SwalHelper.fire({
                icon:  'error',
                title: 'Periksa isian form',
                html,
            });
        }, 300);
    }

    //init

    document.addEventListener('DOMContentLoaded', function () {

        // Form dengan data-ajax 
        document.querySelectorAll('form[data-ajax]').forEach(handleAjaxForm);

        // Tombol konfirmasi delete
        bindConfirmDelete(document);

        // Tombol konfirmasi umum
        bindConfirm(document);

        // Error 422 
        handleBladeErrors();

        // Patch wilayah error handler 
        setTimeout(patchWilayahErrorHandler, 0);
    });
                        
    
    window.FormHandler = {
        
        bindAjax: handleAjaxForm,

        rebind: function (root) {
            root = root || document;
            bindConfirmDelete(root);
            bindConfirm(root);
            root.querySelectorAll('form[data-ajax]').forEach(handleAjaxForm);
        },
    };

})();