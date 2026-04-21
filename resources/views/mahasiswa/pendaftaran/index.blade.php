@extends('layouts.app')

@section('title', 'Status Pendaftaran')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Status Pendaftaran Saya</h2>

        @if(!$pendaftaran)
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-4 text-gray-500">Anda belum mengisi data pendaftaran.</p>
                <a href="{{ route('mahasiswa.pendaftaran.create') }}"
                   class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Isi Data Pendaftaran
                </a>
            </div>
        @else
            <div class="col-span-2 flex justify-end gap-3 mb-4">
                <a href="{{ route('mahasiswa.pendaftaran.cetak-pdf') }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Bukti
                </a>
                <a href="{{ route('mahasiswa.pendaftaran.edit') }}"
                   class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Edit Data
                </a>
            </div>

            {{-- Data Pribadi --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3 border-b border-indigo-100 pb-1">
                    Data Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @php
                        $pribadi = [
                            'Nama Lengkap'    => $pendaftaran->nama_lengkap,
                            'Jenis Kelamin'   => $pendaftaran->jenis_kelamin,
                            'Status Menikah'  => $pendaftaran->status_menikah,
                            'Kewarganegaraan' => $pendaftaran->kewarganegaraan,
                            'Agama'           => $pendaftaran->agama?->nama,
                            'Email'           => $pendaftaran->email,
                            'No. HP'          => $pendaftaran->no_hp,
                            'No. Telepon'     => $pendaftaran->no_telepon ?: '-',
                        ];
                    @endphp
                    @foreach($pribadi as $label => $value)
                        <div class="bg-gray-50 rounded p-3">
                            <p class="text-gray-500 text-xs mb-1">{{ $label }}</p>
                            <p class="text-gray-800 font-medium">{{ $value ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Data Kelahiran --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3 border-b border-indigo-100 pb-1">
                    Data Kelahiran
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @php
                        $lahir = [
                            'Tempat Lahir'         => $pendaftaran->lahir_luar_negeri
                                                        ? '(Luar Negeri) ' . ($pendaftaran->tempat_lahir ?? '-')
                                                        : $pendaftaran->tempat_lahir,
                            'Tanggal Lahir'        => $pendaftaran->tanggal_lahir?->format('d/m/Y'),
                            'Lahir di Luar Negeri' => $pendaftaran->lahir_luar_negeri ? 'Ya' : 'Tidak',
                            'Negara Lahir'         => $pendaftaran->lahir_luar_negeri ? ($pendaftaran->negara_lahir ?? '-') : '-',
                            'Provinsi Lahir'       => $pendaftaran->provinceLahir?->name ?? '-',
                            'Kota Lahir'           => $pendaftaran->cityLahir?->name ?? '-',
                        ];
                    @endphp
                    @foreach($lahir as $label => $value)
                        <div class="bg-gray-50 rounded p-3">
                            <p class="text-gray-500 text-xs mb-1">{{ $label }}</p>
                            <p class="text-gray-800 font-medium">{{ $value ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Alamat KTP --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3 border-b border-indigo-100 pb-1">
                    Alamat KTP
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @php
                        $alamatKtp = [
                            'Alamat KTP'    => $pendaftaran->alamat_ktp,
                            'Provinsi KTP'  => $pendaftaran->province?->name,
                            'Kota KTP'      => $pendaftaran->city?->name,
                            'Kecamatan KTP' => $pendaftaran->district?->name,
                            'Kelurahan KTP' => $pendaftaran->village?->name,
                        ];
                    @endphp
                    @foreach($alamatKtp as $label => $value)
                        <div class="bg-gray-50 rounded p-3">
                            <p class="text-gray-500 text-xs mb-1">{{ $label }}</p>
                            <p class="text-gray-800 font-medium">{{ $value ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Alamat Sekarang --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3 border-b border-indigo-100 pb-1">
                    Alamat Sekarang
                </h3>
                @if($pendaftaran->same_as_ktp)
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded p-3 text-sm">
                        Sama dengan Alamat KTP
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        @php
                            $alamatSekarang = [
                                'Alamat Sekarang'    => $pendaftaran->alamat_sekarang,
                                'Provinsi Sekarang'  => $pendaftaran->provinceSekarang?->name,
                                'Kota Sekarang'      => $pendaftaran->citySekarang?->name,
                                'Kecamatan Sekarang' => $pendaftaran->districtSekarang?->name,
                                'Kelurahan Sekarang' => $pendaftaran->villageSekarang?->name,
                            ];
                        @endphp
                        @foreach($alamatSekarang as $label => $value)
                            <div class="bg-gray-50 rounded p-3">
                                <p class="text-gray-500 text-xs mb-1">{{ $label }}</p>
                                <p class="text-gray-800 font-medium">{{ $value ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Berkas / Media --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3 border-b border-indigo-100 pb-1">
                    Berkas & Media
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-gray-500 text-xs mb-1">Foto</p>
                        @if($pendaftaran->foto)
                            <img src="{{ Storage::url($pendaftaran->foto) }}"
                                 alt="Foto"
                                 class="h-24 w-24 object-cover rounded border border-gray-200 mt-1">
                        @else
                            <p class="text-gray-800 font-medium">-</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-gray-500 text-xs mb-1">Video Perkenalan</p>
                        @if($pendaftaran->video_perkenalan)
                            <a href="{{ Storage::url($pendaftaran->video_perkenalan) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 text-indigo-600 hover:underline font-medium mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14.752 11.168l-6.586-3.796A1 1 0 007 8.232v7.536a1 1 0 001.166.964l6.586-1.902A1 1 0 0016 13.87v-2.74a1 1 0 00-1.248-.962z"/>
                                </svg>
                                Lihat Video
                            </a>
                        @else
                            <p class="text-gray-800 font-medium">-</p>
                        @endif
                    </div>
                </div>
            </div>

        @endif
    </div>
</div>
@endsection