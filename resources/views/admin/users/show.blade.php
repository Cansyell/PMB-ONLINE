@extends('layouts.app')
@section('title', 'Detail User')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke daftar user</a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Detail User</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100">
                    Edit
                </a>
                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                          onsubmit="return confirm('Hapus user ini?')">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="p-6 space-y-6">
            {{-- Avatar + Info Utama --}}
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <span class="mt-1 inline-block px-2 py-0.5 text-xs font-medium rounded-full
                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            {{-- Info Akun --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-3">
                    Informasi Akun
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach([
                        'ID User'          => '#' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'Terdaftar'        => $user->created_at->format('d/m/Y H:i'),
                        'Terakhir Diperbarui' => $user->updated_at->format('d/m/Y H:i'),
                        'Email Verified'   => $user->email_verified_at ? $user->email_verified_at->format('d/m/Y') : 'Belum diverifikasi',
                    ] as $label => $value)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-400 mb-1">{{ $label }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Data Pendaftaran --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b pb-2 mb-3">
                    Data Pendaftaran Mahasiswa
                </h3>
                @if($user->mahasiswa)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach([
                            'Nama Lengkap' => $user->mahasiswa->nama_lengkap,
                            'Agama'        => $user->mahasiswa->agama?->nama,
                            'Provinsi KTP' => $user->mahasiswa->province?->name,
                            'Kota KTP'     => $user->mahasiswa->city?->name,
                            'No. HP'       => $user->mahasiswa->no_hp,
                            'Email'        => $user->mahasiswa->email,
                        ] as $label => $value)
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-400 mb-1">{{ $label }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $value ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.pendaftaran.show', $user->mahasiswa->id) }}"
                           class="text-sm text-indigo-600 hover:underline">
                            Lihat detail pendaftaran lengkap &rarr;
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">User ini belum mengisi data pendaftaran.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection