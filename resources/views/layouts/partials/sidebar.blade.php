<!-- Overlay mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-gray-800 text-white flex-shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-30 flex flex-col">

    {{-- Header Sidebar --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-700">
        <div>
            <h1 class="text-lg font-bold leading-tight">
                @if(auth()->user()->role === 'admin')
                    Admin Panel
                @else
                    Portal Mahasiswa
                @endif
            </h1>
            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ auth()->user()->name }}</p>
        </div>
        {{-- Tombol tutup (mobile) --}}
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto py-4">

        @if(auth()->user()->role === 'admin')
     
         {{-- admin --}}

            <p class="px-6 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu</p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-6 py-3 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white border-r-2 border-indigo-400' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Daftar Pendaftar --}}
            <a href="{{ route('admin.pendaftaran.index') }}"
               class="flex items-center px-6 py-3 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-gray-700 text-white border-r-2 border-indigo-400' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Daftar Pendaftar
            </a>

            {{-- Kelola User --}}
            <a href="{{ route('admin.users.index') }}"
            class="flex items-center px-6 py-3 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white border-r-2 border-indigo-400' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kelola User
            </a>

            {{-- Monitoring --}}
            <a href="{{ route('admin.monitoring.index') }}"
            class="flex items-center px-6 py-3 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.monitoring.*') ? 'bg-gray-700 text-white border-r-2 border-indigo-400' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Monitoring
            </a>

        @else
        
        {{-- MENU MAHASISWA                             --}}

            <p class="px-6 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu</p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-6 py-3 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white border-r-2 border-indigo-400' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>

            {{-- Data Pendaftaran --}}
            <a href="{{ route('mahasiswa.pendaftaran.index') }}"
               class="flex items-center px-6 py-3 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('mahasiswa.pendaftaran.*') ? 'bg-gray-700 text-white border-r-2 border-indigo-400' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Data Pendaftaran
            </a>

        @endif

    </nav>

    {{-- Footer Sidebar: Logout --}}
    <div class="border-t border-gray-700 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center w-full px-3 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

</aside>