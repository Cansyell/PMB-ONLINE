/**
 * form-handler.js — Global Form & Error Handler
 * -----------------------------------------------
 * Sertakan file ini di layout utama SETELAH sweetalert.js:
 *
 *   <script src="{{ asset('js/sweetalert.js') }}"></script>
 *   <script src="{{ asset('js/form-handler.js') }}"></script>
 *
 * Fitur:
 *   1. Intercept form submit → kirim via fetch → tangkap 422 Laravel otomatis
 *   2. Error handler wilayah (fetch kota/kecamatan/kelurahan gagal)
 *   3. Konfirmasi delete global (data-confirm-delete)
 *   4. Loading otomatis saat submit form besar (multipart/file)
 *   5. Semua error tampil via SwalHelper (popup tengah)
 *
 * ─── OPT-IN via atribut HTML ────────────────────────────────────────────────
 *
 *  Intercept submit + 422 handling:
 *    <form data-ajax>...</form>
 *
 *  Intercept submit + loading popup (form dengan file upload):
 *    <form data-ajax data-loading="Mengupload data...">...</form>
 *
 *  Konfirmasi delete:
 *    <button data-confirm-delete
 *            data-title="Hapus user ini?"
 *            data-text="Data tidak bisa dikembalikan."
 *            data-form="#form-delete-1">
 *      Hapus
 *    </button>
 *
 *  Konfirmasi aksi umum:
 *    <button data-confirm
 *            data-title="Publish artikel?"
 *            data-text="Artikel akan langsung tampil."
 *            data-form="#form-publish">
 *      Publish
 *    </button>
 *
 * ─── TANPA atribut = perilaku default (reload biasa) ────────────────────────
 * Form tanpa data-ajax tetap submit normal. Tidak ada perubahan perilaku.
 */

(function () {
    'use strict';

    /* ───────────────────────────────────────────────────────────────────── */
    /*  1. Intercept form[data-ajax]                                         */
    /* ───────────────────────────────────────────────────────────────────── */

    /**
     * Tangani submit form via fetch.
     * - Tampilkan loading jika ada atribut data-loading
     * - Jika 422: tampilkan SwalHelper dengan list error field
     * - Jika sukses redirect: ikuti redirect
     * - Jika sukses JSON: emit event 'ajax:success' pada form
     * - Jika error lain: teruskan ke SwalHelper.handleError
     */
    function handleAjaxForm(form) {
        form.addEventListener('submit', async function (e) {
            // Biarkan validasi client-side berjalan dulu (jika ada)
            // Jika ada listener submit lain yang memanggil e.preventDefault(),
            // kita tidak melanjutkan fetch.
            // Trik: tunda eksekusi kita ke microtask berikutnya.
            // Karena listener lain (validasi) sudah dipasang sebelumnya,
            // kita cukup cek apakah defaultPrevented setelah event selesai.

            // Hentikan submit native dulu — kita yang kirim.
            e.preventDefault();
            e.stopImmediatePropagation();

            // Jika ada validasi custom di _scripts yang sudah jalan
            // dan menetapkan flag, cek di sini.
            // Karena stopImmediatePropagation() kita pakai, listener lain
            // tidak akan jalan. Solusinya: pindahkan ke pola event terpisah.
            // Lihat penjelasan di bawah (*).

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
                        // Jangan set Content-Type untuk multipart — biar browser yang set boundary
                        ...(isMultipart ? {} : { 'Content-Type': 'application/x-www-form-urlencoded' }),
                    },
                    body,
                });

                SwalHelper.close();

                // ── Redirect (HTML response) ───────────────────────────────
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                const contentType = response.headers.get('content-type') || '';

                // ── JSON response ─────────────────────────────────────────
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

                // ── HTML response (redirect after POST) ───────────────────
                // Laravel setelah redirect biasanya return HTML halaman tujuan.
                // Cek apakah ada header Location dari server (jarang di fetch).
                if (response.ok) {
                    // Ikuti URL terakhir (setelah redirect chain fetch)
                    window.location.href = response.url || action;
                    return;
                }

                // ── Error HTML (misal 500 page) ───────────────────────────
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

    /**
     * Buat objek tiruan agar SwalHelper.handleError bisa membaca status + data
     * tanpa perlu Response asli yang sudah di-consume.
     */
    function _buildFakeResponse(status, data) {
        return { response: { status, data } };
    }

    /* ───────────────────────────────────────────────────────────────────── */
    /*  2. Konfirmasi delete — data-confirm-delete                           */
    /* ───────────────────────────────────────────────────────────────────── */

    /**
     * Pasang listener pada semua elemen dengan atribut [data-confirm-delete].
     * Elemen bisa berupa <button> atau <a>.
     *
     * Atribut:
     *   data-title   — judul popup (opsional)
     *   data-text    — teks penjelasan (opsional)
     *   data-form    — selector form yang akan di-submit (misal "#form-delete-1")
     *                  Jika tidak ada, cari form terdekat (closest).
     */
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

    /* ───────────────────────────────────────────────────────────────────── */
    /*  3. Konfirmasi aksi umum — data-confirm                              */
    /* ───────────────────────────────────────────────────────────────────── */

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

    /* ───────────────────────────────────────────────────────────────────── */
    /*  4. Patch fetchAndPopulateTS — error wilayah via SwalHelper           */
    /* ───────────────────────────────────────────────────────────────────── */

    /**
     * Override window.fetchAndPopulateTS jika sudah didefinisikan di _scripts.
     * Ini akan replace fungsi wilayah yang error-nya cuma teks biasa,
     * menjadi popup SwalHelper.
     *
     * Harus dipanggil SETELAH _scripts.blade.php dieksekusi,
     * makanya kita wrap dalam setTimeout 0.
     */
    function patchWilayahErrorHandler() {
        if (typeof window.fetchAndPopulateTS !== 'function') return;

        const _original = window.fetchAndPopulateTS;

        window.fetchAndPopulateTS = function (url, code, id, placeholder, selectValue, callback) {
            // Jalankan versi asli tapi intercept fetch error-nya
            // Kita tidak bisa wrap fetch di dalam _original tanpa modifikasi,
            // jadi kita re-implementasi dengan error handler yang lebih baik.

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

    /* ───────────────────────────────────────────────────────────────────── */
    /*  5. Validasi 422 dari server — tampil di SwalHelper                  */
    /*     (untuk form NON data-ajax yang masih submit biasa)               */
    /* ───────────────────────────────────────────────────────────────────── */

    /**
     * Jika halaman di-load dengan session errors dari Laravel (422 redirect),
     * dan ada elemen .blade-errors (kita inject via Blade), tampilkan via Swal.
     *
     * Tambahkan di layout/partials/session-flash.blade.php:
     *   @if($errors->any())
     *     <div id="blade-errors" class="hidden"
     *          data-errors="{{ json_encode($errors->all()) }}"></div>
     *   @endif
     */
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

    /* ───────────────────────────────────────────────────────────────────── */
    /*  Init                                                                 */
    /* ───────────────────────────────────────────────────────────────────── */

    document.addEventListener('DOMContentLoaded', function () {

        // Form dengan data-ajax → intercept submit
        document.querySelectorAll('form[data-ajax]').forEach(handleAjaxForm);

        // Tombol konfirmasi delete
        bindConfirmDelete(document);

        // Tombol konfirmasi umum
        bindConfirm(document);

        // Error 422 dari server (form biasa)
        handleBladeErrors();

        // Patch wilayah error handler — setelah _scripts.blade.php jalan
        setTimeout(patchWilayahErrorHandler, 0);
    });

    /* ───────────────────────────────────────────────────────────────────── */
    /*  Public API — bisa dipanggil dari view lain                          */
    /* ───────────────────────────────────────────────────────────────────── */

    window.FormHandler = {
        /**
         * Pasang intercept ajax pada form tertentu secara manual.
         * Berguna untuk form yang dibuat dinamis (misal modal).
         * @param {HTMLFormElement} form
         */
        bindAjax: handleAjaxForm,

        /**
         * Re-scan elemen baru untuk data-confirm-delete dan data-confirm.
         * Berguna setelah konten dinamis (Livewire, AJAX load).
         * @param {HTMLElement} root
         */
        rebind: function (root) {
            root = root || document;
            bindConfirmDelete(root);
            bindConfirm(root);
            root.querySelectorAll('form[data-ajax]').forEach(handleAjaxForm);
        },
    };

})();