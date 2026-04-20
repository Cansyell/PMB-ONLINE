<script>
const API = {
    cities:    '{{ route("api.wilayah.cities",    ":code") }}',
    districts: '{{ route("api.wilayah.districts", ":code") }}',
    villages:  '{{ route("api.wilayah.villages",  ":code") }}',
};

// ── Tom Select Registry ───────────────────────────────────────────────────
// Menyimpan semua instance agar bisa di-destroy/refresh
const TS = {};

/**
 * Inisialisasi Tom Select pada sebuah <select>.
 * Jika sudah ada instance sebelumnya, destroy dulu.
 */
function initTS(id, placeholder) {
    if (TS[id]) { TS[id].destroy(); }

    TS[id] = new TomSelect('#' + id, {
        placeholder:      placeholder,
        allowEmptyOption: true,
        searchField:      ['text'],
        maxOptions:       500,
        render: {
            no_results: () => `<div class="no-results">Tidak ditemukan</div>`,
        },
    });
}

/**
 * Reset Tom Select: kosongkan opsi, set placeholder, disable.
 */
function resetTS(id, placeholder) {
    if (TS[id]) {
        TS[id].clear(true);
        TS[id].clearOptions();
        TS[id].settings.placeholder = placeholder;
        TS[id].inputState();
        TS[id].disable();
    }
}

/**
 * Isi Tom Select dengan data dari API lalu enable.
 * Setelah diisi, callback(data) dipanggil jika ada.
 */
function fetchAndPopulateTS(url, code, id, placeholder, callback) {
    if (TS[id]) {
        TS[id].clear(true);
        TS[id].clearOptions();
        TS[id].disable();
    }

    fetch(url.replace(':code', code))
        .then(r => r.json())
        .then(data => {
            if (!TS[id]) return;
            data.forEach(item => TS[id].addOption({ value: item.code, text: item.name }));
            TS[id].refreshOptions(false);
            TS[id].enable();
            if (callback) callback(data);
        })
        .catch(() => {
            if (TS[id]) {
                TS[id].addOption({ value: '', text: '-- Gagal memuat --' });
                TS[id].refreshOptions(false);
            }
        });
}

// ── Inisialisasi semua Tom Select ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

    // Provinsi (semua langsung aktif karena datanya dari Blade, bukan API)
    initTS('province_code_lahir',    '-- Pilih Provinsi --');
    initTS('province_code',          '-- Pilih Provinsi --');
    initTS('province_code_sekarang', '-- Pilih Provinsi --');

    // Kota/Kab (disabled dulu, diaktifkan setelah provinsi dipilih)
    initTS('city_code_lahir',    '-- Pilih Provinsi dulu --');
    initTS('city_code',          '-- Pilih Provinsi dulu --');
    initTS('city_code_sekarang', '-- Pilih Provinsi dulu --');
    TS['city_code_lahir'].disable();
    TS['city_code'].disable();
    TS['city_code_sekarang'].disable();

    // Kecamatan
    initTS('district_code',          '-- Pilih Kota dulu --');
    initTS('district_code_sekarang', '-- Pilih Kota dulu --');
    TS['district_code'].disable();
    TS['district_code_sekarang'].disable();

    // Kelurahan
    initTS('village_code',          '-- Pilih Kecamatan dulu --');
    initTS('village_code_sekarang', '-- Pilih Kecamatan dulu --');
    TS['village_code'].disable();
    TS['village_code_sekarang'].disable();

    // ── Repopulate saat edit ──────────────────────────────────────────────

    // KTP
    const savedProvKtp  = '{{ isset($pendaftaran) ? $pendaftaran->province_code : "" }}';
    const savedCityKtp  = '{{ isset($pendaftaran) ? $pendaftaran->city_code : "" }}';
    const savedDistKtp  = '{{ isset($pendaftaran) ? $pendaftaran->district_code : "" }}';
    const savedVilKtp   = '{{ isset($pendaftaran) ? $pendaftaran->village_code : "" }}';

    if (savedProvKtp) {
        // Provinsi sudah ter-render dari Blade, cukup set value
        TS['province_code'].setValue(savedProvKtp, true);

        fetchAndPopulateTS(API.cities, savedProvKtp, 'city_code', '-- Pilih Kota/Kabupaten --', () => {
            if (savedCityKtp) {
                TS['city_code'].setValue(savedCityKtp, true);
                fetchAndPopulateTS(API.districts, savedCityKtp, 'district_code', '-- Pilih Kecamatan --', () => {
                    if (savedDistKtp) {
                        TS['district_code'].setValue(savedDistKtp, true);
                        fetchAndPopulateTS(API.villages, savedDistKtp, 'village_code', '-- Pilih Kelurahan/Desa --', () => {
                            if (savedVilKtp) TS['village_code'].setValue(savedVilKtp, true);
                        });
                    }
                });
            }
        });
    }

    // Lahir (hanya jika dalam negeri)
    const savedProvLahir = '{{ isset($pendaftaran) ? $pendaftaran->province_code_lahir : "" }}';
    const savedCityLahir = '{{ isset($pendaftaran) ? $pendaftaran->city_code_lahir : "" }}';
    const isLuarNegeri   = {{ isset($pendaftaran) && $pendaftaran->lahir_luar_negeri ? 'true' : 'false' }};

    if (savedProvLahir && !isLuarNegeri) {
        TS['province_code_lahir'].setValue(savedProvLahir, true);
        fetchAndPopulateTS(API.cities, savedProvLahir, 'city_code_lahir', '-- Pilih Kota/Kabupaten --', () => {
            if (savedCityLahir) TS['city_code_lahir'].setValue(savedCityLahir, true);
        });
    }

    // Sekarang
    const savedProvNow = '{{ isset($pendaftaran) ? $pendaftaran->province_code_sekarang : "" }}';
    const savedCityNow = '{{ isset($pendaftaran) ? $pendaftaran->city_code_sekarang : "" }}';
    const savedDistNow = '{{ isset($pendaftaran) ? $pendaftaran->district_code_sekarang : "" }}';
    const savedVilNow  = '{{ isset($pendaftaran) ? $pendaftaran->village_code_sekarang : "" }}';

    if (savedProvNow) {
        TS['province_code_sekarang'].setValue(savedProvNow, true);
        fetchAndPopulateTS(API.cities, savedProvNow, 'city_code_sekarang', '-- Pilih Kota/Kabupaten --', () => {
            if (savedCityNow) {
                TS['city_code_sekarang'].setValue(savedCityNow, true);
                fetchAndPopulateTS(API.districts, savedCityNow, 'district_code_sekarang', '-- Pilih Kecamatan --', () => {
                    if (savedDistNow) {
                        TS['district_code_sekarang'].setValue(savedDistNow, true);
                        fetchAndPopulateTS(API.villages, savedDistNow, 'village_code_sekarang', '-- Pilih Kelurahan/Desa --', () => {
                            if (savedVilNow) TS['village_code_sekarang'].setValue(savedVilNow, true);
                        });
                    }
                });
            }
        });
    }
});

// ── Chaining KTP ──────────────────────────────────────────────────────────
document.getElementById('province_code').addEventListener('change', function () {
    resetTS('city_code',     '-- Pilih Kota/Kabupaten --');
    resetTS('district_code', '-- Pilih Kota dulu --');
    resetTS('village_code',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.cities, this.value, 'city_code', '-- Pilih Kota/Kabupaten --');
});

document.getElementById('city_code').addEventListener('change', function () {
    resetTS('district_code', '-- Pilih Kecamatan --');
    resetTS('village_code',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.districts, this.value, 'district_code', '-- Pilih Kecamatan --');
});

document.getElementById('district_code').addEventListener('change', function () {
    resetTS('village_code', '-- Pilih Kelurahan/Desa --');
    if (!this.value) return;
    fetchAndPopulateTS(API.villages, this.value, 'village_code', '-- Pilih Kelurahan/Desa --');
});

// ── Chaining Lahir ────────────────────────────────────────────────────────
document.getElementById('province_code_lahir').addEventListener('change', function () {
    resetTS('city_code_lahir', '-- Pilih Kota/Kabupaten --');
    if (!this.value) return;
    fetchAndPopulateTS(API.cities, this.value, 'city_code_lahir', '-- Pilih Kota/Kabupaten --');
});

// ── Chaining Sekarang ─────────────────────────────────────────────────────
document.getElementById('province_code_sekarang').addEventListener('change', function () {
    resetTS('city_code_sekarang',     '-- Pilih Kota/Kabupaten --');
    resetTS('district_code_sekarang', '-- Pilih Kota dulu --');
    resetTS('village_code_sekarang',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.cities, this.value, 'city_code_sekarang', '-- Pilih Kota/Kabupaten --');
});

document.getElementById('city_code_sekarang').addEventListener('change', function () {
    resetTS('district_code_sekarang', '-- Pilih Kecamatan --');
    resetTS('village_code_sekarang',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.districts, this.value, 'district_code_sekarang', '-- Pilih Kecamatan --');
});

document.getElementById('district_code_sekarang').addEventListener('change', function () {
    resetTS('village_code_sekarang', '-- Pilih Kelurahan/Desa --');
    if (!this.value) return;
    fetchAndPopulateTS(API.villages, this.value, 'village_code_sekarang', '-- Pilih Kelurahan/Desa --');
});

// ── Toggle Lahir Luar Negeri ──────────────────────────────────────────────
const checkLuarNegeri     = document.getElementById('lahir_luar_negeri');
const sectionWilayahLahir = document.getElementById('section-wilayah-lahir');
const sectionNegaraLahir  = document.getElementById('section-negara-lahir');

function toggleLahirLuarNegeri() {
    const luarNegeri = checkLuarNegeri.checked;

    if (luarNegeri) {
        sectionNegaraLahir.classList.remove('hidden');
        sectionWilayahLahir.classList.add('hidden');

        // Kosongkan & disable Tom Select wilayah lahir
        if (TS['province_code_lahir']) {
            TS['province_code_lahir'].clear(true);
            TS['province_code_lahir'].disable();
        }
        resetTS('city_code_lahir', '-- Pilih Provinsi dulu --');
    } else {
        sectionWilayahLahir.classList.remove('hidden');
        sectionNegaraLahir.classList.add('hidden');

        // Aktifkan kembali provinsi lahir
        if (TS['province_code_lahir']) TS['province_code_lahir'].enable();

        document.getElementById('negara_lahir').value = '';
    }
}

checkLuarNegeri.addEventListener('change', toggleLahirLuarNegeri);
toggleLahirLuarNegeri();

// ── Checkbox "Sama dengan KTP" ────────────────────────────────────────────
const checkSame  = document.getElementById('same_as_ktp');
const sectionNow = document.getElementById('section-alamat-sekarang');

function toggleAlamatSekarang() {
    if (checkSame.checked) {
        sectionNow.style.display = 'none';
        sectionNow.querySelectorAll('input, select').forEach(el => el.removeAttribute('required'));
    } else {
        sectionNow.style.display = 'block';
    }
}

checkSame.addEventListener('change', toggleAlamatSekarang);
toggleAlamatSekarang();

// ── Validasi Email ─────────────────────────────────────────────────────────
const emailInput = document.getElementById('input-email');
const emailError = document.getElementById('email-error');

emailInput.addEventListener('blur', function () {
    const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
    emailError.classList.toggle('hidden', !this.value || ok);
    this.classList.toggle('border-red-400', this.value && !ok);
});

// ── Validasi Angka (HP & Telepon) ──────────────────────────────────────────
function setupNumericInput(inputId, errorId) {
    const el  = document.getElementById(inputId);
    const err = document.getElementById(errorId);
    el.addEventListener('input', () => { el.value = el.value.replace(/[^0-9]/g, ''); });
    el.addEventListener('blur',  () => {
        const bad = el.value && !/^\d+$/.test(el.value);
        err.classList.toggle('hidden', !bad);
        el.classList.toggle('border-red-400', !!bad);
    });
}

setupNumericInput('input-hp',      'hp-error');
setupNumericInput('input-telepon', 'telepon-error');

// ── Preview & Validasi Foto ────────────────────────────────────────────────
const inputFoto     = document.getElementById('input-foto');
const fotoPreview   = document.getElementById('foto-preview');
const fotoWrapper   = document.getElementById('foto-preview-wrapper');
const fotoSizeError = document.getElementById('foto-size-error');
const MAX_FOTO      = 2 * 1024 * 1024;

inputFoto.addEventListener('change', function () {
    const file = this.files[0];
    fotoSizeError.classList.add('hidden');
    this.classList.remove('border-red-400');

    if (!file) { fotoWrapper.classList.add('hidden'); return; }

    if (file.size > MAX_FOTO) {
        fotoSizeError.classList.remove('hidden');
        this.classList.add('border-red-400');
        this.value = '';
        fotoWrapper.classList.add('hidden');
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        fotoPreview.src = e.target.result;
        fotoWrapper.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});

// ── Preview & Validasi Video ──────────────────────────────────────────────
const inputVideo     = document.getElementById('input-video');
const videoPreview   = document.getElementById('video-preview');
const videoWrapper   = document.getElementById('video-preview-wrapper');
const videoSizeError = document.getElementById('video-size-error');
const MAX_VIDEO      = 50 * 1024 * 1024;

inputVideo.addEventListener('change', function () {
    const file = this.files[0];
    videoSizeError.classList.add('hidden');
    this.classList.remove('border-red-400');

    if (!file) { videoWrapper.classList.add('hidden'); return; }

    if (file.size > MAX_VIDEO) {
        videoSizeError.classList.remove('hidden');
        this.classList.add('border-red-400');
        this.value = '';
        videoWrapper.classList.add('hidden');
        return;
    }

    videoPreview.src = URL.createObjectURL(file);
    videoWrapper.classList.remove('hidden');
});

// ── Cegah submit jika ada error client ────────────────────────────────────
document.getElementById('form-pendaftaran').addEventListener('submit', function (e) {
    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
    if (!emailOk) {
        emailError.classList.remove('hidden');
        e.preventDefault();
    }
});
</script>