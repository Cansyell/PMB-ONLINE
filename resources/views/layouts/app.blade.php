<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
        {{-- Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <style>
        .ts-wrapper.single .ts-control {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            border-color: #d1d5db;
            box-shadow: none;
            cursor: pointer;
        }
        .ts-wrapper.single.focus .ts-control {
            border-color: #818cf8;
            box-shadow: 0 0 0 2px #a5b4fc;
        }
        .ts-wrapper.disabled .ts-control {
            background-color: #f9fafb;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .ts-dropdown {
            border-radius: 0.5rem;
            font-size: 0.875rem;
            border-color: #d1d5db;
        }
        .ts-dropdown .option.active {
            background-color: #6366f1;
        }
        .ts-dropdown .option:hover {
            background-color: #e0e7ff;
            color: #3730a3;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
    @include('layouts.partials.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden min-h-0">
        @include('layouts.partials.navbar')

        <main class="flex-1 overflow-y-auto p-6 min-h-0">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar when clicking overlay
        document.getElementById('sidebar-overlay')?.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>