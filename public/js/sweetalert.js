
const SwalHelper = (() => {

    // Konfigurasi tema                                                    
    const BRAND  = '#4f46e5';   
    const DANGER = '#ef4444';   
    const GRAY   = '#6b7280';   

    const DEFAULTS = {
        position:           'center',   
        confirmButtonColor: BRAND,
        cancelButtonColor:  GRAY,
        buttonsStyling:     true,
        allowOutsideClick:  true,
        allowEscapeKey:     true,
        scrollbarPadding:   false,
    };

    //  Core                                                                
    function fire(options) {
        return Swal.fire({ ...DEFAULTS, ...options });
    }

    // Notifikasi sederhana (modal di tengah)                            
    function success(title, text = '') {
        return fire({ icon: 'success', title, text });
    }

    function error(title, text = '') {
        return fire({ icon: 'error', title, text });
    }

    function warning(title, text = '') {
        return fire({ icon: 'warning', title, text });
    }

    function info(title, text = '') {
        return fire({ icon: 'info', title, text });
    }

    
    // Toast — pojok kanan atas (non-blocking)                            
    function toast(icon, title, timer = 3000) {
        return Swal.fire({
            toast:             true,
            position:          'top-end',
            showConfirmButton: false,
            timer,
            timerProgressBar:  true,
            icon,
            title,
            didOpen: (t) => {
                t.addEventListener('mouseenter', Swal.stopTimer);
                t.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });
    }

    
    //  Konfirmasi umum                                                     
    function confirm(title, text, onConfirm, onCancel = null, options = {}) {
        return fire({
            icon:              'question',
            title,
            text,
            showCancelButton:  true,
            confirmButtonText: options.confirmText ?? 'Ya, lanjutkan',
            cancelButtonText:  options.cancelText  ?? 'Batal',
            reverseButtons:    true,
            ...options,
        }).then(result => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm(result);
            } else if (result.isDismissed && typeof onCancel === 'function') {
                onCancel(result);
            }
        });
    }

    
    //  Konfirmasi hapus                                                    
    function confirmDelete(
        title     = 'Hapus data ini?',
        text      = 'Data tidak bisa dikembalikan setelah dihapus.',
        onConfirm,
        options   = {}
    ) {
        return fire({
            icon:               'warning',
            title,
            text,
            showCancelButton:   true,
            confirmButtonText:  options.confirmText ?? 'Ya, hapus!',
            cancelButtonText:   options.cancelText  ?? 'Batal',
            confirmButtonColor: DANGER,
            reverseButtons:     true,
            ...options,
        }).then(result => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm(result);
            }
        });
    }

    
    // Loading — non-dismissable saat proses berjalan                      
    
    function loading(title = 'Memproses...', text = 'Mohon tunggu sebentar.') {
        return Swal.fire({
            ...DEFAULTS,
            title,
            text,
            allowOutsideClick: false,
            allowEscapeKey:    false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading(),
        });
    }

    // Tutup popup yang sedang terbuka 
    function close() {
        Swal.close();
    }

    
    // Error handler               
    async function handleError(
        err,
        fallbackTitle = 'Terjadi Kesalahan',
        fallbackText  = 'Silakan coba lagi atau hubungi administrator.'
    ) {
        // String langsung 
        if (typeof err === 'string') {
            return error(fallbackTitle, err);
        }

        // Native fetch Response 
        if (err instanceof Response) {
            return _handleHttpResponse(err, fallbackTitle, fallbackText);
        }

        // Axios error 
        if (err && err.response) {
            return _handleHttpStatus(
                err.response.status,
                err.response.data,
                fallbackTitle,
                fallbackText
            );
        }

        // Network / koneksi mati 
        if (err instanceof TypeError && err.message.includes('fetch')) {
            return error(
                'Koneksi Bermasalah',
                'Tidak dapat terhubung ke server. Periksa koneksi internet kamu.'
            );
        }

        // Error object biasa 
        if (err instanceof Error) {
            return error(fallbackTitle, err.message || fallbackText);
        }

        // Fallback 
        return error(fallbackTitle, fallbackText);
    }

    // Parse native fetch Response 
    async function _handleHttpResponse(response, fallbackTitle, fallbackText) {
        let data = null;
        try {
            const ct = response.headers.get('content-type') || '';
            data = ct.includes('application/json') ? await response.json() : null;
        } catch (_) { }

        return _handleHttpStatus(response.status, data, fallbackTitle, fallbackText);
    }

    // Routing berdasarkan HTTP status 
    function _handleHttpStatus(status, data, fallbackTitle, fallbackText) {
        switch (status) {
            case 401:
                return fire({
                    icon:              'warning',
                    title:             'Sesi Habis',
                    text:              'Silakan login kembali untuk melanjutkan.',
                    confirmButtonText: 'Login',
                }).then(result => {
                    if (result.isConfirmed) window.location.href = '/login';
                });

            case 403:
                return error(
                    'Akses Ditolak',
                    data?.message ?? 'Kamu tidak memiliki izin untuk melakukan tindakan ini.'
                );

            case 404:
                return error(
                    'Data Tidak Ditemukan',
                    data?.message ?? 'Data yang kamu cari tidak tersedia.'
                );

            case 422:
                return _handleValidationErrors(data);

            case 429:
                return error(
                    'Terlalu Banyak Permintaan',
                    'Silakan tunggu beberapa saat sebelum mencoba lagi.'
                );

            case 500:
            case 503:
                return error(
                    'Kesalahan Server',
                    data?.message ?? 'Terjadi kesalahan pada server. Coba lagi nanti.'
                );

            default:
                return error(fallbackTitle, data?.message ?? fallbackText);
        }
    }

    // Tampilkan Laravel 422 validation errors sebagai list 
    function _handleValidationErrors(data) {
        if (!data || !data.errors) {
            return error(
                'Validasi Gagal',
                data?.message ?? 'Periksa kembali isian form kamu.'
            );
        }

        // Kumpulkan semua pesan error menjadi list HTML
        const messages = Object.values(data.errors).flat();
        const html = '<ul style="text-align:left;margin:0;padding-left:1.2rem">'
            + messages.map(m => `<li>${m}</li>`).join('')
            + '</ul>';

        return fire({
            icon:  'error',
            title: data.message ?? 'Validasi Gagal',
            html,
        });
    }

    
    // Wrapper fetch — loading + error handling otomatis                  
    async function fetchWithLoading(url, fetchOptions = {}, uiOptions = {}) {
        const {
            loadingTitle      = 'Memproses...',
            loadingText       = 'Mohon tunggu sebentar.',
            showSuccessToast  = true,
            successMessage    = 'Berhasil!',
        } = uiOptions;

        loading(loadingTitle, loadingText);

        try {
            const response = await fetch(url, fetchOptions);
            Swal.close();

            if (!response.ok) {
                await _handleHttpResponse(response, 'Permintaan Gagal', 'Coba lagi nanti.');
                return null;
            }

            const contentType = response.headers.get('content-type') || '';
            const data = contentType.includes('application/json')
                ? await response.json()
                : null;

            if (showSuccessToast) toast('success', successMessage);

            return data;
        } catch (err) {
            Swal.close();
            await handleError(err);
            return null;
        }
    }

    
    // Utility                                                             
    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    }

    
    // Public API                                                          
    return {
        fire,
        success,
        error,
        warning,
        info,
        toast,
        confirm,
        confirmDelete,
        loading,
        close,
        handleError,
        fetchWithLoading,
        csrfToken,
    };

})();