@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')
<div class="bg-white rounded-lg shadow">

    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Kelola User</h2>
        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
            + Tambah User
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="px-6 py-3 border-b border-gray-100 flex flex-wrap gap-3">
        <form method="GET" class="flex flex-wrap gap-3 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau email..."
                   class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64">
            <select name="role"
                    class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">Semua Role</option>
                <option value="admin"     {{ request('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                <option value="mahasiswa" {{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
            <button type="submit"
                    class="px-4 py-1.5 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-1.5 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data Pendaftaran</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dibuat</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $i => $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $users->firstItem() + $i }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="text-xs text-gray-400">(Anda)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @if($user->role === 'admin')
                                <span class="px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">
                                    Admin
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                    Mahasiswa
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-center">
                            @if($user->mahasiswa_count > 0)
                                <span class="text-green-600 font-medium">Sudah mengisi</span>
                            @else
                                <span class="text-gray-400">Belum mengisi</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="px-3 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100">
                                    Detail
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="px-3 py-1 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100">
                                    Edit
                                </a>
                                @if($user->id !== auth()->id())
                                    <button class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100" data-confirm-delete 
                                            data-title="Hapus user ini?"
                                            data-text="Data tidak bisa dikembalikan."
                                            data-form="#form-delete-{{ $user->id }}">
                                        Hapus
                                    </button>

                                    <form id="form-delete-{{ $user->id }}" method="POST" 
                                        action="{{ route('admin.users.destroy', $user->id) }}">
                                        @csrf @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            Tidak ada user ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    @endif

</div>
@endsection