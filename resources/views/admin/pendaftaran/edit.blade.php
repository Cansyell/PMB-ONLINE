@extends('layouts.app')

@section('title', 'Edit Data Pendaftar')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-4">
        <a href="{{ route('admin.pendaftaran.show', $pendaftaran->id) }}"
           class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke detail</a>
    </div>

    <div class="bg-white rounded-lg shadow">

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Data Pendaftar</h2>
            <p class="text-sm text-gray-500 mt-1">
                Mengedit data: <span class="font-medium text-gray-700">{{ $pendaftaran->nama_lengkap }}</span>
            </p>
        </div>

        <form method="POST"
              action="{{ route('admin.pendaftaran.update', $pendaftaran->id) }}"
              class="p-6"
              id="form-pendaftaran"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm font-medium text-red-700 mb-2">Terdapat kesalahan pengisian:</p>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Reuse partial form yang sama dengan mahasiswa --}}
            @include('mahasiswa.pendaftaran._form')

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.pendaftaran.show', $pendaftaran->id) }}"
                   class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Reuse scripts wilayah + validasi yang sama --}}
@include('mahasiswa.pendaftaran._scripts')
@endpush