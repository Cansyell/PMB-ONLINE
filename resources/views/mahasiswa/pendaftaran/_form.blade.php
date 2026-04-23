@php
    // Helper: nilai lama atau dari model (untuk edit)
    $old = fn($key, $default = null) => old($key, isset($pendaftaran) ? $pendaftaran->{$key} : $default);
@endphp

{{-- DATA PRIBADI                                                    --}}

<h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-4">
    Data Pribadi
</h3>

{{-- Nama Lengkap --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Nama Lengkap <span class="text-red-500">*</span>
    </label>
    <input type="text" name="nama_lengkap" value="{{ $old('nama_lengkap') }}"
           class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('nama_lengkap') ? 'border-red-400' : 'border-gray-300' }}"
           placeholder="Nama sesuai KTP">
    @error('nama_lengkap') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    {{-- Jenis Kelamin --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Jenis Kelamin <span class="text-red-500">*</span>
        </label>
        <select name="jenis_kelamin"
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('jenis_kelamin') ? 'border-red-400' : 'border-gray-300' }}">
            <option value="">-- Pilih --</option>
            @foreach(['Laki-laki', 'Perempuan'] as $jk)
                <option value="{{ $jk }}" {{ $old('jenis_kelamin') == $jk ? 'selected' : '' }}>{{ $jk }}</option>
            @endforeach
        </select>
        @error('jenis_kelamin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Status Menikah --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Status Menikah <span class="text-red-500">*</span>
        </label>
        <select name="status_menikah"
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('status_menikah') ? 'border-red-400' : 'border-gray-300' }}">
            <option value="">-- Pilih --</option>
            @foreach(['Belum Menikah', 'Menikah', 'Cerai'] as $status)
                <option value="{{ $status }}" {{ $old('status_menikah') == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
        @error('status_menikah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Kewarganegaraan --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kewarganegaraan <span class="text-red-500">*</span>
        </label>
        <select name="kewarganegaraan"
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('kewarganegaraan') ? 'border-red-400' : 'border-gray-300' }}">
            <option value="WNI" {{ $old('kewarganegaraan', 'WNI') == 'WNI' ? 'selected' : '' }}>WNI</option>
            <option value="WNA" {{ $old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA</option>
        </select>
        @error('kewarganegaraan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Agama --}}
<div class="mb-4 md:w-1/3">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Agama <span class="text-red-500">*</span>
    </label>
    <select name="agama_id"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('agama_id') ? 'border-red-400' : 'border-gray-300' }}">
        <option value="">-- Pilih Agama --</option>
        @foreach($agamas as $agama)
            <option value="{{ $agama->id }}" {{ $old('agama_id') == $agama->id ? 'selected' : '' }}>
                {{ $agama->nama }}
            </option>
        @endforeach
    </select>
    @error('agama_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>


{{-- DATA KELAHIRAN--}}
<h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-4 mt-8">
    Data Kelahiran
</h3>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    {{-- Tempat Lahir --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Tempat Lahir <span class="text-red-500">*</span>
        </label>
        <input type="text" name="tempat_lahir" value="{{ $old('tempat_lahir') }}"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('tempat_lahir') ? 'border-red-400' : 'border-gray-300' }}"
               placeholder="Nama kota/kabupaten">
        @error('tempat_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Tanggal Lahir --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Tanggal Lahir <span class="text-red-500">*</span>
        </label>
        <input type="date" name="tanggal_lahir"
               value="{{ old('tanggal_lahir', isset($pendaftaran) ? $pendaftaran->tanggal_lahir?->format('Y-m-d') : '') }}"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('tanggal_lahir') ? 'border-red-400' : 'border-gray-300' }}">
        @error('tanggal_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Toggle Lahir Luar Negeri --}}
<div class="mb-4">
    <label class="inline-flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="lahir_luar_negeri" id="lahir_luar_negeri" value="1"
               class="w-4 h-4 rounded border-gray-300 text-indigo-600"
               {{ old('lahir_luar_negeri', isset($pendaftaran) && $pendaftaran->lahir_luar_negeri ? '1' : '') == '1' ? 'checked' : '' }}>
        <span class="text-sm text-gray-700 font-medium">Lahir di Luar Negeri</span>
    </label>
</div>

{{-- Wilayah Dalam Negeri --}}
<div id="section-wilayah-lahir">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Provinsi Lahir <span class="text-red-500">*</span>
            </label>
            <select name="province_code_lahir" id="province_code_lahir"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('province_code_lahir') ? 'border-red-400' : 'border-gray-300' }}">
                <option value="">-- Pilih Provinsi --</option>
                @foreach($provinces as $prov)
                    <option value="{{ $prov->code }}" {{ $old('province_code_lahir') == $prov->code ? 'selected' : '' }}>
                        {{ $prov->name }}
                    </option>
                @endforeach
            </select>
            @error('province_code_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kota/Kab. Lahir <span class="text-red-500">*</span>
            </label>
            <select name="city_code_lahir" id="city_code_lahir"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('city_code_lahir') ? 'border-red-400' : 'border-gray-300' }}" disabled>
                <option value="">-- Pilih Provinsi dulu --</option>
                @if(isset($pendaftaran) && $pendaftaran->city_code_lahir)
                    <option value="{{ $pendaftaran->city_code_lahir }}" selected>
                        {{ $pendaftaran->cityLahir?->name }}
                    </option>
                @endif
            </select>
            @error('city_code_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

{{-- Wilayah Luar Negeri --}}
<div id="section-negara-lahir" class="hidden">
    <div class="mb-4 md:w-1/2">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Negara Lahir <span class="text-red-500">*</span>
        </label>
        <input type="text" name="negara_lahir" id="negara_lahir"
               value="{{ $old('negara_lahir', isset($pendaftaran) ? $pendaftaran->negara_lahir : '') }}"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('negara_lahir') ? 'border-red-400' : 'border-gray-300' }}"
               placeholder="Contoh: Malaysia, Jepang, dll.">
        @error('negara_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>


{{-- ALAMAT KTP--}}
<h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-4 mt-8">
    Alamat KTP
</h3>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Alamat KTP <span class="text-red-500">*</span>
    </label>
    <input type="text" name="alamat_ktp" id="alamat_ktp" value="{{ $old('alamat_ktp') }}"
           class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('alamat_ktp') ? 'border-red-400' : 'border-gray-300' }}"
           placeholder="Jalan, RT/RW, No. Rumah">
    @error('alamat_ktp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    {{-- Provinsi KTP --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Provinsi <span class="text-red-500">*</span>
        </label>
        <select name="province_code" id="province_code"
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('province_code') ? 'border-red-400' : 'border-gray-300' }}">
            <option value="">-- Pilih Provinsi --</option>
            @foreach($provinces as $prov)
                <option value="{{ $prov->code }}" {{ $old('province_code') == $prov->code ? 'selected' : '' }}>
                    {{ $prov->name }}
                </option>
            @endforeach
        </select>
        @error('province_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Kota KTP --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kota/Kabupaten <span class="text-red-500">*</span>
        </label>
        <select name="city_code" id="city_code"
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('city_code') ? 'border-red-400' : 'border-gray-300' }}" disabled>
            <option value="">-- Pilih Provinsi dulu --</option>
            @if(isset($pendaftaran) && $pendaftaran->city_code)
                <option value="{{ $pendaftaran->city_code }}" selected>{{ $pendaftaran->city?->name }}</option>
            @endif
        </select>
        @error('city_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Kecamatan KTP --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kecamatan <span class="text-red-500">*</span>
        </label>
        <select name="district_code" id="district_code"
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('district_code') ? 'border-red-400' : 'border-gray-300' }}" disabled>
            <option value="">-- Pilih Kota dulu --</option>
            @if(isset($pendaftaran) && $pendaftaran->district_code)
                <option value="{{ $pendaftaran->district_code }}" selected>{{ $pendaftaran->district?->name }}</option>
            @endif
        </select>
        @error('district_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Kelurahan KTP --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Kelurahan/Desa <span class="text-red-500">*</span>
        </label>
        <select name="village_code" id="village_code"
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('village_code') ? 'border-red-400' : 'border-gray-300' }}" disabled>
            <option value="">-- Pilih Kecamatan dulu --</option>
            @if(isset($pendaftaran) && $pendaftaran->village_code)
                <option value="{{ $pendaftaran->village_code }}" selected>{{ $pendaftaran->village?->name }}</option>
            @endif
        </select>
        @error('village_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>


{{-- ALAMAT SAAT INI--}}
<h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-4 mt-8">
    Alamat Saat Ini
</h3>

{{-- Checkbox sama dengan KTP --}}
<div class="mb-4">
    <label class="inline-flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="same_as_ktp" id="same_as_ktp" value="1"
               class="w-4 h-4 rounded border-gray-300 text-indigo-600"
               {{ old('same_as_ktp', isset($pendaftaran) && $pendaftaran->same_as_ktp ? '1' : '') == '1' ? 'checked' : '' }}>
        <span class="text-sm text-gray-700 font-medium">Sama dengan Alamat KTP</span>
    </label>
</div>

<div id="section-alamat-sekarang">
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Alamat Saat Ini <span class="text-red-500">*</span>
        </label>
        <input type="text" name="alamat_sekarang" id="alamat_sekarang"
               value="{{ $old('alamat_sekarang', isset($pendaftaran) ? $pendaftaran->alamat_sekarang : '') }}"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('alamat_sekarang') ? 'border-red-400' : 'border-gray-300' }}"
               placeholder="Jalan, RT/RW, No. Rumah">
        @error('alamat_sekarang') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        {{-- Provinsi Sekarang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Provinsi <span class="text-red-500">*</span>
            </label>
            <select name="province_code_sekarang" id="province_code_sekarang"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('province_code_sekarang') ? 'border-red-400' : 'border-gray-300' }}">
                <option value="">-- Pilih Provinsi --</option>
                @foreach($provinces as $prov)
                    <option value="{{ $prov->code }}"
                        {{ old('province_code_sekarang', isset($pendaftaran) ? $pendaftaran->province_code_sekarang : '') == $prov->code ? 'selected' : '' }}>
                        {{ $prov->name }}
                    </option>
                @endforeach
            </select>
            @error('province_code_sekarang') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Kota Sekarang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kota/Kabupaten <span class="text-red-500">*</span>
            </label>
            <select name="city_code_sekarang" id="city_code_sekarang"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('city_code_sekarang') ? 'border-red-400' : 'border-gray-300' }}" disabled>
                <option value="">-- Pilih Provinsi dulu --</option>
                @if(isset($pendaftaran) && $pendaftaran->city_code_sekarang)
                    <option value="{{ $pendaftaran->city_code_sekarang }}" selected>{{ $pendaftaran->citySekarang?->name }}</option>
                @endif
            </select>
            @error('city_code_sekarang') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Kecamatan Sekarang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kecamatan <span class="text-red-500">*</span>
            </label>
            <select name="district_code_sekarang" id="district_code_sekarang"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('district_code_sekarang') ? 'border-red-400' : 'border-gray-300' }}" disabled>
                <option value="">-- Pilih Kota dulu --</option>
                @if(isset($pendaftaran) && $pendaftaran->district_code_sekarang)
                    <option value="{{ $pendaftaran->district_code_sekarang }}" selected>{{ $pendaftaran->districtSekarang?->name }}</option>
                @endif
            </select>
            @error('district_code_sekarang') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Kelurahan Sekarang --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kelurahan/Desa <span class="text-red-500">*</span>
            </label>
            <select name="village_code_sekarang" id="village_code_sekarang"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('village_code_sekarang') ? 'border-red-400' : 'border-gray-300' }}" disabled>
                <option value="">-- Pilih Kecamatan dulu --</option>
                @if(isset($pendaftaran) && $pendaftaran->village_code_sekarang)
                    <option value="{{ $pendaftaran->village_code_sekarang }}" selected>{{ $pendaftaran->villageSekarang?->name }}</option>
                @endif
            </select>
            @error('village_code_sekarang') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>


{{-- KONTAK--}}
<h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-4 mt-8">
    Kontak
</h3>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    {{-- Email --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Email <span class="text-red-500">*</span>
        </label>
        <input type="text" name="email" id="input-email" value="{{ $old('email') }}"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}"
               placeholder="email@domain.com">
        <p id="email-error" class="mt-1 text-xs text-red-600 hidden">Isian tidak sesuai format email.</p>
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- No Telepon --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
        <input type="text" name="no_telepon" id="input-telepon" value="{{ $old('no_telepon') }}"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('no_telepon') ? 'border-red-400' : 'border-gray-300' }}"
               placeholder="021xxxxxxx">
        <p id="telepon-error" class="mt-1 text-xs text-red-600 hidden">Isian harus format angka.</p>
        @error('no_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- No HP --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            No. HP <span class="text-red-500">*</span>
        </label>
        <input type="text" name="no_hp" id="input-hp" value="{{ $old('no_hp') }}"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('no_hp') ? 'border-red-400' : 'border-gray-300' }}"
               placeholder="08xxxxxxxxxx">
        <p id="hp-error" class="mt-1 text-xs text-red-600 hidden">Isian harus format angka.</p>
        @error('no_hp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

{{-- FOTO & VIDEO--}}
<h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-4 mt-8">
    Foto & Video Perkenalan
</h3>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">

    {{-- Foto --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Foto <span class="text-red-500">*</span>
        </label>
        <p class="text-xs text-gray-400 mb-2">Format: JPG, JPEG, PNG. Maks. 2 MB.</p>

        {{-- Preview foto existing (mode edit) --}}
        @if(isset($pendaftaran) && $pendaftaran->foto)
            <div class="mb-2" id="foto-preview-existing">
                <img src="{{ Storage::url($pendaftaran->foto) }}"
                     alt="Foto"
                     class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
            </div>
        @endif

        {{-- Preview foto baru --}}
        <div id="foto-preview-wrapper" class="mb-2 hidden">
            <img id="foto-preview" src="#" alt="Preview"
                 class="w-32 h-32 object-cover rounded-lg border border-gray-200">
        </div>

        <input type="file" name="foto" id="input-foto"
               accept="image/jpeg,image/jpg,image/png"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('foto') ? 'border-red-400' : 'border-gray-300' }}">
        <p id="foto-size-error" class="mt-1 text-xs text-red-600 hidden">Ukuran file melebihi 2 MB.</p>
        @error('foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Video Perkenalan --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Video Perkenalan <span class="text-red-500">*</span>
        </label>
        <p class="text-xs text-gray-400 mb-2">Format: MP4. Maks. 50 MB.</p>

        {{-- Preview video existing (mode edit) --}}
        @if(isset($pendaftaran) && $pendaftaran->video_perkenalan)
            <div class="mb-2" id="video-preview-existing">
                <video controls class="w-full rounded-lg border border-gray-200 max-h-40">
                    <source src="{{ Storage::url($pendaftaran->video_perkenalan) }}" type="video/mp4">
                </video>
                <p class="text-xs text-gray-500 mt-1">Video saat ini. Upload baru untuk mengganti.</p>
            </div>
        @endif

        {{-- Preview video baru --}}
        <div id="video-preview-wrapper" class="mb-2 hidden">
            <video id="video-preview" controls
                   class="w-full rounded-lg border border-gray-200 max-h-40">
            </video>
        </div>

        <input type="file" name="video_perkenalan" id="input-video"
               accept="video/mp4"
               class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('video_perkenalan') ? 'border-red-400' : 'border-gray-300' }}">
        <p id="video-size-error" class="mt-1 text-xs text-red-600 hidden">Ukuran file melebihi 50 MB.</p>
        @error('video_perkenalan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

</div>