<script>

const API = {
    cities:    '{{ route("api.wilayah.cities",    ":code") }}',
    districts: '{{ route("api.wilayah.districts", ":code") }}',
    villages:  '{{ route("api.wilayah.villages",  ":code") }}',
};

const TS = {};

function initTS(id, placeholder, disabled = true) {
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
    if (disabled) TS[id].disable();
}

function resetTS(id, placeholder) {
    if (!TS[id]) return;
    TS[id].clear(true);
    TS[id].clearOptions();
    TS[id].settings.placeholder = placeholder;
    TS[id].inputState();
    TS[id].disable();
}

function fetchAndPopulateTS(url, code, id, placeholder, selectValue, callback) {
    if (!TS[id]) return;
    TS[id].clear(true);
    TS[id].clearOptions();
    TS[id].disable();

    fetch(url.replace(':code', code))
        .then(r => r.json())
        .then(data => {
            if (!TS[id]) return;
            data.forEach(item => TS[id].addOption({ value: item.code, text: item.name }));
            TS[id].refreshOptions(false);
            TS[id].enable();
            if (selectValue) TS[id].setValue(selectValue, true); // silent
            if (callback) callback();
        })
        .catch(() => {
            if (!TS[id]) return;
            TS[id].addOption({ value: '', text: '-- Gagal memuat --' });
            TS[id].refreshOptions(false);
            TS[id].enable();
        });
}

function oldVal(name) {
    const el = document.getElementById('old_' + name);
    return el ? el.value : '';
}

document.addEventListener('DOMContentLoaded', function () {

    // sebagai <option selected> dari Blade.
    initTS('province_code_lahir',    '-- Pilih Provinsi --',    false);
    initTS('province_code',          '-- Pilih Provinsi --',    false);
    initTS('province_code_sekarang', '-- Pilih Provinsi --',    false);

    // disabled sampai province dipilih 
    initTS('city_code_lahir',    '-- Pilih Provinsi dulu --');
    initTS('city_code',          '-- Pilih Provinsi dulu --');
    initTS('city_code_sekarang', '-- Pilih Provinsi dulu --');

    initTS('district_code',          '-- Pilih Kota dulu --');
    initTS('district_code_sekarang', '-- Pilih Kota dulu --');

    initTS('village_code',          '-- Pilih Kecamatan dulu --');
    initTS('village_code_sekarang', '-- Pilih Kecamatan dulu --');

    // KTP 
    const provKtp = TS['province_code']?.getValue();
    const cityKtp = oldVal('city_code');
    const distKtp = oldVal('district_code');
    const vilKtp  = oldVal('village_code');

    if (provKtp) {
        fetchAndPopulateTS(API.cities, provKtp, 'city_code', '-- Pilih Kota/Kabupaten --', cityKtp, () => {
            if (!cityKtp) return;
            fetchAndPopulateTS(API.districts, cityKtp, 'district_code', '-- Pilih Kecamatan --', distKtp, () => {
                if (!distKtp) return;
                fetchAndPopulateTS(API.villages, distKtp, 'village_code', '-- Pilih Kelurahan/Desa --', vilKtp, null);
            });
        });
    }

    // Lahir 
    const luarNegeriChecked = document.getElementById('lahir_luar_negeri').checked;
    const provLahir = TS['province_code_lahir']?.getValue();
    const cityLahir = oldVal('city_code_lahir');

    if (provLahir && !luarNegeriChecked) {
        fetchAndPopulateTS(API.cities, provLahir, 'city_code_lahir', '-- Pilih Kota/Kabupaten --', cityLahir, null);
    }

    // Sekarang 
    const provNow = TS['province_code_sekarang']?.getValue();
    const cityNow = oldVal('city_code_sekarang');
    const distNow = oldVal('district_code_sekarang');
    const vilNow  = oldVal('village_code_sekarang');

    if (provNow) {
        fetchAndPopulateTS(API.cities, provNow, 'city_code_sekarang', '-- Pilih Kota/Kabupaten --', cityNow, () => {
            if (!cityNow) return;
            fetchAndPopulateTS(API.districts, cityNow, 'district_code_sekarang', '-- Pilih Kecamatan --', distNow, () => {
                if (!distNow) return;
                fetchAndPopulateTS(API.villages, distNow, 'village_code_sekarang', '-- Pilih Kelurahan/Desa --', vilNow, null);
            });
        });
    }
});

// Chaining KTP 
document.getElementById('province_code').addEventListener('change', function () {
    resetTS('city_code',     '-- Pilih Kota/Kabupaten --');
    resetTS('district_code', '-- Pilih Kota dulu --');
    resetTS('village_code',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.cities, this.value, 'city_code', '-- Pilih Kota/Kabupaten --', null, null);
});

document.getElementById('city_code').addEventListener('change', function () {
    resetTS('district_code', '-- Pilih Kecamatan --');
    resetTS('village_code',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.districts, this.value, 'district_code', '-- Pilih Kecamatan --', null, null);
});

document.getElementById('district_code').addEventListener('change', function () {
    resetTS('village_code', '-- Pilih Kelurahan/Desa --');
    if (!this.value) return;
    fetchAndPopulateTS(API.villages, this.value, 'village_code', '-- Pilih Kelurahan/Desa --', null, null);
});

// Chaining Lahir 
document.getElementById('province_code_lahir').addEventListener('change', function () {
    resetTS('city_code_lahir', '-- Pilih Kota/Kabupaten --');
    if (!this.value) return;
    fetchAndPopulateTS(API.cities, this.value, 'city_code_lahir', '-- Pilih Kota/Kabupaten --', null, null);
});

// Chaining Sekarang 
document.getElementById('province_code_sekarang').addEventListener('change', function () {
    resetTS('city_code_sekarang',     '-- Pilih Kota/Kabupaten --');
    resetTS('district_code_sekarang', '-- Pilih Kota dulu --');
    resetTS('village_code_sekarang',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.cities, this.value, 'city_code_sekarang', '-- Pilih Kota/Kabupaten --', null, null);
});

document.getElementById('city_code_sekarang').addEventListener('change', function () {
    resetTS('district_code_sekarang', '-- Pilih Kecamatan --');
    resetTS('village_code_sekarang',  '-- Pilih Kecamatan dulu --');
    if (!this.value) return;
    fetchAndPopulateTS(API.districts, this.value, 'district_code_sekarang', '-- Pilih Kecamatan --', null, null);
});

document.getElementById('district_code_sekarang').addEventListener('change', function () {
    resetTS('village_code_sekarang', '-- Pilih Kelurahan/Desa --');
    if (!this.value) return;
    fetchAndPopulateTS(API.villages, this.value, 'village_code_sekarang', '-- Pilih Kelurahan/Desa --', null, null);
});

// Toggle Lahir Luar Negeri 
const checkLuarNegeri     = document.getElementById('lahir_luar_negeri');
const sectionWilayahLahir = document.getElementById('section-wilayah-lahir');
const sectionNegaraLahir  = document.getElementById('section-negara-lahir');

function toggleLahirLuarNegeri() {
    const luarNegeri = checkLuarNegeri.checked;
    if (luarNegeri) {
        sectionNegaraLahir.classList.remove('hidden');
        sectionWilayahLahir.classList.add('hidden');
        if (TS['province_code_lahir']) {
            TS['province_code_lahir'].clear(true);
            TS['province_code_lahir'].disable();
        }
        resetTS('city_code_lahir', '-- Pilih Provinsi dulu --');
    } else {
        sectionWilayahLahir.classList.remove('hidden');
        sectionNegaraLahir.classList.add('hidden');
        if (TS['province_code_lahir']) TS['province_code_lahir'].enable();
        document.getElementById('negara_lahir').value = '';
    }
}

checkLuarNegeri.addEventListener('change', toggleLahirLuarNegeri);

// (toggleLahirLuarNegeri dipanggil di akhir DOMContentLoaded 

// Checkbox "Sama dengan KTP" 
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

// Panggil toggle lahir 
toggleLahirLuarNegeri();

// Validasi Email (real-time blur) 
const emailInput = document.getElementById('input-email');

emailInput.addEventListener('blur', function () {
    const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
    this.classList.toggle('border-red-400', this.value && !ok);
});

// Validasi Angka (HP & Telepon) 
function setupNumericInput(inputId) {
    const el = document.getElementById(inputId);
    el.addEventListener('input', () => { el.value = el.value.replace(/[^0-9]/g, ''); });
}

setupNumericInput('input-hp');
setupNumericInput('input-telepon');

// Preview & Validasi Foto 
const inputFoto   = document.getElementById('input-foto');
const fotoPreview = document.getElementById('foto-preview');
const fotoWrapper = document.getElementById('foto-preview-wrapper');
const MAX_FOTO    = 2 * 1024 * 1024;

inputFoto.addEventListener('change', function () {
    const file = this.files[0];
    this.classList.remove('border-red-400');
    if (!file) { fotoWrapper.classList.add('hidden'); return; }

    if (file.size > MAX_FOTO) {
        this.value = '';
        fotoWrapper.classList.add('hidden');
        SwalHelper.warning('File terlalu besar', 'Ukuran foto melebihi batas maksimal 2 MB.');
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        fotoPreview.src = e.target.result;
        fotoWrapper.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});

//  Preview & Validasi Video 
const inputVideo   = document.getElementById('input-video');
const videoPreview = document.getElementById('video-preview');
const videoWrapper = document.getElementById('video-preview-wrapper');
const MAX_VIDEO    = 50 * 1024 * 1024;

inputVideo.addEventListener('change', function () {
    const file = this.files[0];
    this.classList.remove('border-red-400');
    if (!file) { videoWrapper.classList.add('hidden'); return; }

    if (file.size > MAX_VIDEO) {
        this.value = '';
        videoWrapper.classList.add('hidden');
        SwalHelper.warning('File terlalu besar', 'Ukuran video melebihi batas maksimal 50 MB.');
        return;
    }

    videoPreview.src = URL.createObjectURL(file);
    videoWrapper.classList.remove('hidden');
});

// Peta label field ramah pengguna 
const FIELD_LABELS = {
    'nama_lengkap':           'Nama Lengkap',
    'jenis_kelamin':          'Jenis Kelamin',
    'status_menikah':         'Status Menikah',
    'kewarganegaraan':        'Kewarganegaraan',
    'agama_id':               'Agama',
    'tempat_lahir':           'Tempat Lahir',
    'tanggal_lahir':          'Tanggal Lahir',
    'negara_lahir':           'Negara Lahir',
    'province_code_lahir':    'Provinsi Lahir',
    'city_code_lahir':        'Kota/Kabupaten Lahir',
    'alamat_ktp':             'Alamat KTP',
    'province_code':          'Provinsi (KTP)',
    'city_code':              'Kota/Kabupaten (KTP)',
    'district_code':          'Kecamatan (KTP)',
    'village_code':           'Kelurahan/Desa (KTP)',
    'alamat_sekarang':        'Alamat Sekarang',
    'province_code_sekarang': 'Provinsi (Sekarang)',
    'city_code_sekarang':     'Kota/Kabupaten (Sekarang)',
    'district_code_sekarang': 'Kecamatan (Sekarang)',
    'village_code_sekarang':  'Kelurahan/Desa (Sekarang)',
    'email':                  'Email',
    'input-email':            'Email',
    'no_hp':                  'No. HP',
    'input-hp':               'No. HP',
    'input-foto':             'Foto',
    'foto':                   'Foto',
    'input-video':            'Video Perkenalan',
    'video_perkenalan':       'Video Perkenalan',
};

function getFieldLabel(el) {
    if (el.name && FIELD_LABELS[el.name]) return FIELD_LABELS[el.name];
    if (el.id   && FIELD_LABELS[el.id])   return FIELD_LABELS[el.id];
    if (el.id) {
        const label = document.querySelector(`label[for="${el.id}"]`);
        if (label) return label.textContent.trim().replace(/\s*\*\s*$/, '').trim();
    }
    return el.placeholder || el.name || el.id || 'Field tidak diketahui';
}

function isFieldVisible(el) {
    if (el.disabled) return false;
    let node = el;
    while (node && node !== document.body) {
        const style = window.getComputedStyle(node);
        if (
            style.display    === 'none'   ||
            style.visibility === 'hidden' ||
            node.classList.contains('hidden')
        ) return false;
        node = node.parentElement;
    }
    return true;
}

// Validasi menyeluruh saat submit 
document.getElementById('form-pendaftaran').addEventListener('submit', function (e) {
    const errors  = [];
    const seen    = new Set(); // hindari duplikat label

    const addError = (label) => {
        if (!seen.has(label)) { seen.add(label); errors.push(label); }
    };

    //  yang terlihat dan kosong
    this.querySelectorAll('[required]').forEach(el => {
        if (!isFieldVisible(el)) return;

        const isEmpty = (el.type === 'file')
            ? el.files.length === 0
            : el.value.trim() === '';

        if (isEmpty) {
            addError(getFieldLabel(el));
            el.classList.add('border-red-400');
        } else {
            el.classList.remove('border-red-400');
        }
    });

    // Format email (isian ada tapi format salah)
    const emailVal = emailInput.value.trim();
    const emailOk  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal);
    if (emailVal && !emailOk) {
        addError('Email (format tidak valid)');
        emailInput.classList.add('border-red-400');
    }

    // Tampilkan semua error sekaligus, batalkan submit
    if (errors.length > 0) {
        e.preventDefault();

        const listHtml = errors
            .map(label => `<li style="padding:3px 0;">• ${label}</li>`)
            .join('');

        SwalHelper.fire({
            icon:  'error',
            title: 'Form belum lengkap',
            html:  `<p style="margin-bottom:8px;color:#6b7280;">
                        Harap lengkapi atau perbaiki field berikut:
                    </p>
                    <ul style="margin:0;padding:0;list-style:none;
                               font-size:0.9em;color:#374151;text-align:left;
                               max-height:240px;overflow-y:auto;">
                        ${listHtml}
                    </ul>`,
        });
    }
});
</script>