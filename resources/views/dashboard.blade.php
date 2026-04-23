@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@php
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
@endphp

{{-- Greeting --}}
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">
        Selamat datang, {{ $user->name }} 👋
    </h2>
    <p class="text-sm text-gray-500 mt-1">
        Anda login sebagai
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
            {{ $isAdmin ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
            {{ ucfirst($user->role) }}
        </span>
        &mdash; {{ now()->translatedFormat('l, d F Y') }}
    </p>
</div>


{{-- ADMIN DASHBOARD--}}

@if($isAdmin)

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Total Pendaftar --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Total Pendaftar</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_pendaftar'] }}</p>
            </div>
        </div>

        {{-- Pendaftar Hari Ini --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Pendaftar Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pendaftar_hari_ini'] }}</p>
            </div>
        </div>

        {{-- Total User --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Total User</p>
                <p class="text-2xl font-bold text="gray-800">{{ $stats['total_user'] }}</p>
            </div>
        </div>

        {{-- Belum Mengisi --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Belum Mengisi Data</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['belum_mengisi'] }}</p>
            </div>
        </div>

    </div>

    {{-- Konten bawah: Tabel + Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Pendaftar Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Pendaftar Terbaru</h3>
                <a href="{{ route('admin.pendaftaran.index') }}"
                   class="text-xs text-indigo-600 hover:underline">Lihat semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase">Nama</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase">Provinsi</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pendaftarTerbaru as $p)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <a href="{{ route('admin.pendaftaran.show', $p->id) }}"
                                           class="font-medium text-gray-800 hover:text-indigo-600">
                                            {{ $p->nama_lengkap }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">
                                    {{ $p->province?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-400 text-xs">
                                    {{ $p->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-xs">
                                    Belum ada pendaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kolom kanan --}}
        <div class="flex flex-col gap-4">

            {{-- Breakdown Gender --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Jenis Kelamin</h3>
                @php
                    $total = $stats['total_pendaftar'] ?: 1;
                    $lakiPct = round($stats['laki_laki'] / $total * 100);
                    $perempuanPct = 100 - $lakiPct;
                @endphp
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Laki-laki</span>
                            <span class="font-medium text-gray-700">{{ $stats['laki_laki'] }} ({{ $lakiPct }}%)</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full"
                                 style="width: {{ $lakiPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Perempuan</span>
                            <span class="font-medium text-gray-700">{{ $stats['perempuan'] }} ({{ $perempuanPct }}%)</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-pink-400 rounded-full"
                                 style="width: {{ $perempuanPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Breakdown Kewarganegaraan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Kewarganegaraan</h3>
                <div class="space-y-2">
                    @foreach($stats['kewarganegaraan'] as $kwn)
                        @php $pct = round($kwn->total / $total * 100); @endphp
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>{{ $kwn->kewarganegaraan }}</span>
                                <span class="font-medium text-gray-700">{{ $kwn->total }} ({{ $pct }}%)</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-teal-400 rounded-full"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Link Cepat --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Akses Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.pendaftaran.index') }}"
                       class="flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Daftar Pendaftar
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Kelola User
                    </a>
                    <a href="{{ route('admin.users.create') }}"
                       class="flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Tambah User Baru
                    </a>
                </div>
            </div>

        </div>
    </div>


{{-- MAHASISWA DASHBOARD--}}

@else

    @php $pendaftaran = $user->mahasiswa; @endphp

    {{-- Status Card --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- Status Pendaftaran --}}
        <div class="sm:col-span-2 bg-white rounded-xl border p-5
            {{ $pendaftaran ? 'border-green-200 bg-green-50' : 'border-amber-200 bg-amber-50' }}">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $pendaftaran ? 'bg-green-100' : 'bg-amber-100' }}">
                    @if($pendaftaran)
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-semibold {{ $pendaftaran ? 'text-green-800' : 'text-amber-800' }}">
                        {{ $pendaftaran ? 'Data Pendaftaran Lengkap' : 'Data Pendaftaran Belum Diisi' }}
                    </p>
                    <p class="text-xs mt-1 {{ $pendaftaran ? 'text-green-600' : 'text-amber-600' }}">
                        {{ $pendaftaran
                            ? 'Data Anda telah tersimpan sejak ' . $pendaftaran->created_at->translatedFormat('d F Y')
                            : 'Segera lengkapi data pendaftaran Anda.' }}
                    </p>
                    <div class="mt-3">
                        @if($pendaftaran)
                            <a href="{{ route('mahasiswa.pendaftaran.index') }}"
                               class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-100 border border-green-200 px-3 py-1.5 rounded-lg hover:bg-green-200">
                                Lihat Data Saya &rarr;
                            </a>
                        @else
                            <a href="{{ route('mahasiswa.pendaftaran.create') }}"
                               class="inline-flex items-center gap-1 text-xs font-medium text-white bg-amber-500 px-3 py-1.5 rounded-lg hover:bg-amber-600">
                                Isi Data Sekarang &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Profil Singkat --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>
            </div>
            <div class="space-y-1.5 text-xs text-gray-500">
                <div class="flex justify-between">
                    <span>Role</span>
                    <span class="font-medium text-blue-600">Mahasiswa</span>
                </div>
                <div class="flex justify-between">
                    <span>Bergabung</span>
                    <span class="font-medium text-gray-700">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                @if($pendaftaran)
                    <div class="flex justify-between">
                        <span>No. HP</span>
                        <span class="font-medium text-gray-700">{{ $pendaftaran->no_hp }}</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Detail data jika sudah mengisi --}}
    @if($pendaftaran)
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @foreach([
                ['label' => 'Nama Lengkap',  'value' => $pendaftaran->nama_lengkap,         'icon' => 'user'],
                ['label' => 'Agama',          'value' => $pendaftaran->agama?->nama ?? '-',  'icon' => 'book'],
                ['label' => 'Provinsi KTP',   'value' => $pendaftaran->province?->name ?? '-', 'icon' => 'map'],
            ] as $item)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-400 mb-1">{{ $item['label'] }}</p>
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Aksi Cepat Mahasiswa --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('mahasiswa.pendaftaran.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Data
                </a>
                <a href="{{ route('mahasiswa.pendaftaran.edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Data
                </a>
                <a href="{{ route('mahasiswa.pendaftaran.cetak-pdf') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Bukti PDF
                </a>
            </div>
        </div>
    @endif

@endif

@endsection