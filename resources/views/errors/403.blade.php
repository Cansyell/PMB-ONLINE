@extends('layouts.app')
@section('title', 'Akses Ditolak')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <p class="text-6xl font-bold text-indigo-200">403</p>
        <h1 class="mt-4 text-2xl font-semibold text-gray-800">Akses Ditolak</h1>
        <p class="mt-2 text-gray-500">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ route('dashboard') }}"
           class="mt-6 inline-block px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection