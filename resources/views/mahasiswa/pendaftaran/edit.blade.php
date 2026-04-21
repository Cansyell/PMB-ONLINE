@extends('layouts.app')

@section('title', 'Edit Data Pendaftaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Data Pendaftaran</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui data pendaftaran Anda.</p>
        </div>

        <form method="POST" action="{{ route('mahasiswa.pendaftaran.update') }}" class="p-6" id="form-pendaftaran" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('layouts.partials.wilayah-old-input')

            @include('mahasiswa.pendaftaran._form')

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('mahasiswa.pendaftaran.index') }}"
                   class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@include('mahasiswa.pendaftaran._scripts')
@endpush