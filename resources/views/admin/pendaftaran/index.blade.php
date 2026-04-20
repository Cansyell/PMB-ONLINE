@extends('layouts.app')

@section('title', 'Daftar Pendaftar')

@section('content')
<div class="bg-white rounded-lg shadow">

    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Mahasiswa Pendaftar</h2>
        <span class="text-sm text-gray-500">Total: {{ $pendaftarans->total() }} pendaftar</span>
    </div>

    @if(session('success'))
        <div class="mx-6 mt-4 p-3 bg-green-100 border border-green-300 text-green-800 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. HP</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Provinsi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pendaftarans as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $pendaftarans->firstItem() + $i }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->nama_lengkap }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->no_hp }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->province?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.pendaftaran.show', $p->id) }}"
                                   class="px-3 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100">
                                    Detail
                                </a>
                                <form method="POST" action="{{ route('admin.pendaftaran.destroy', $p->id) }}"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            Belum ada data pendaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendaftarans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pendaftarans->links() }}
        </div>
    @endif
</div>
@endsection