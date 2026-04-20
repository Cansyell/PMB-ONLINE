@extends('layouts.app')

@section('title', 'Detail Pendaftar')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.pendaftaran.index') }}"
           class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke daftar</a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Detail Pendaftar</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.pendaftaran.edit', $pendaftaran->id) }}"
                class="px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100">
                    Edit Data
                </a>
                <form method="POST" action="{{ route('admin.pendaftaran.destroy', $pendaftaran->id) }}"
                    onsubmit="return confirm('Hapus data pendaftar ini?')">
                    @csrf @method('DELETE')
                    <button class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100">
                        Hapus Data
                    </button>
                </form>
            </div>
        </div>

        <div class="p-6 space-y-8">

            @php
                $sections = [
                    'Data Pribadi' => [
                        'Nama Lengkap'    => $pendaftaran->nama_lengkap,
                        'Jenis Kelamin'   => $pendaftaran->jenis_kelamin,
                        'Status Menikah'  => $pendaftaran->status_menikah,
                        'Kewarganegaraan' => $pendaftaran->kewarganegaraan,
                        'Agama'           => $pendaftaran->agama?->nama,
                    ],
                    'Data Kelahiran' => [
                        'Tempat Lahir'   => $pendaftaran->tempat_lahir,
                        'Tanggal Lahir'  => $pendaftaran->tanggal_lahir?->format('d/m/Y'),
                        'Lahir di Luar Negeri' => $pendaftaran->lahir_luar_negeri ? 'Ya' : 'Tidak',
                        'Negara Lahir'       => $pendaftaran->lahir_luar_negeri ? ($pendaftaran->negara_lahir ?? '-') : '-',
                        'Provinsi Lahir' => $pendaftaran->provinceLahir?->name,
                        'Kota Lahir'     => $pendaftaran->cityLahir?->name,
                    ],
                    'Alamat KTP' => [
                        'Alamat'     => $pendaftaran->alamat_ktp,
                        'Provinsi'   => $pendaftaran->province?->name,
                        'Kota/Kab.'  => $pendaftaran->city?->name,
                        'Kecamatan'  => $pendaftaran->district?->name,
                        'Kelurahan'  => $pendaftaran->village?->name,
                    ],
                    'Alamat Saat Ini' => [
                        'Alamat'    => $pendaftaran->same_as_ktp ? '(Sama dengan KTP)' : $pendaftaran->alamat_sekarang,
                        'Provinsi'  => $pendaftaran->same_as_ktp ? '-' : $pendaftaran->provinceSekarang?->name,
                        'Kota/Kab.' => $pendaftaran->same_as_ktp ? '-' : $pendaftaran->citySekarang?->name,
                        'Kecamatan' => $pendaftaran->same_as_ktp ? '-' : $pendaftaran->districtSekarang?->name,
                        'Kelurahan' => $pendaftaran->same_as_ktp ? '-' : $pendaftaran->villageSekarang?->name,
                    ],
                    'Kontak' => [
                        'Email'       => $pendaftaran->email,
                        'No. HP'      => $pendaftaran->no_hp,
                        'No. Telepon' => $pendaftaran->no_telepon ?: '-',
                    ],
                ];
            @endphp

            @foreach($sections as $title => $fields)
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-3">
                        {{ $title }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($fields as $label => $value)
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-400 mb-1">{{ $label }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $value ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
                {{-- Foto & Video Perkenalan --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-3">
                        Foto & Video Perkenalan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Foto --}}
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Foto</p>
                            @if($pendaftaran->foto)
                                <a href="{{ Storage::url($pendaftaran->foto) }}" target="_blank">
                                    <img src="{{ Storage::url($pendaftaran->foto) }}"
                                        alt="Foto {{ $pendaftaran->nama_lengkap }}"
                                        class="w-48 h-48 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition">
                                </a>
                                <p class="mt-1 text-xs text-gray-400">Klik gambar untuk memperbesar.</p>
                            @else
                                <p class="text-sm text-gray-400 italic">Belum ada foto.</p>
                            @endif
                        </div>

                        {{-- Video Perkenalan --}}
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Video Perkenalan</p>
                            @if($pendaftaran->video_perkenalan)
                                <video controls
                                    class="w-full rounded-lg border border-gray-200 max-h-64">
                                    <source src="{{ Storage::url($pendaftaran->video_perkenalan) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                                <a href="{{ Storage::url($pendaftaran->video_perkenalan) }}"
                                target="_blank"
                                class="mt-2 inline-block text-xs text-indigo-600 hover:underline">
                                    Unduh video
                                </a>
                            @else
                                <p class="text-sm text-gray-400 italic">Belum ada video perkenalan.</p>
                            @endif
                        </div>

                    </div>
                </div>
        </div>
    </div>
</div>
@endsection