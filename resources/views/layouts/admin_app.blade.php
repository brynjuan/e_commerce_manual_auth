<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel E-commerce') }} - Admin Panel - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
    <style>
        /* Custom scrollbar for webkit browsers */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#0a224a] text-gray-100 flex flex-col custom-scrollbar overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-700"> {{-- border-gray-700 (dark gray) should still be visible enough on #0a224a (navy) --}}
                <a href="{{ route('admin.dashboard') }}">
                    <!-- <img src="{{ asset('images/logo.png') }}" alt="StyleNest Admin Logo" class="h-20 w-auto mx-auto mb-6"> {{-- Sesuaikan h-10 --}} -->
                    <img src="{{ asset('images/hmti logo.png') }}" alt="StyleNest Admin Logo" class="h-20 w-auto mx-auto mb-6"> {{-- Sesuaikan h-10 --}}
                </a>
                <h1 class="text-xl font-semibold">Admin Panel</h1>
               
            </div>
            <nav class="flex-1 px-4 py-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-[#0E3A7A] transition-colors duration-200 @if(request()->routeIs('admin.dashboard')) bg-[#081B3A] @endif">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-[#0E3A7A] transition-colors duration-200 @if(request()->routeIs('admin.products.*')) bg-[#081B3A] @endif">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Manajemen Produk
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-[#0E3A7A] transition-colors duration-200 @if(request()->routeIs('admin.users.*')) bg-[#081B3A] @endif">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manajemen Pengguna
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-[#0E3A7A] transition-colors duration-200 @if(request()->routeIs('admin.orders.*')) bg-[#081B3A] @endif">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Manajemen Pesanan
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-gray-700 mt-auto">
                <a href="{{ url('/') }}" target="_blank" class="flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-[#0E3A7A] transition-colors duration-200">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Lihat Situs
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="flex items-center px-2 py-2 mt-2 text-sm font-medium rounded-md hover:bg-[#0E3A7A] transition-colors duration-200">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </a>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="bg-[#0a224a] shadow-md">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
                    <div class="text-gray-100">
                        Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>
                    </div>
                    <!-- Anda bisa menambahkan elemen lain di sini, seperti notifikasi atau tombol menu mobile untuk sidebar -->
                </div>
            </header>
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 sm:p-6 lg:p-8 custom-scrollbar">
                @yield('content')
            </main>

            <footer class="bg-white border-t text-center p-4 text-sm text-gray-600">
                &copy; {{ date('Y') }} Dibuat dengan ❤️ oleh Briant Juan Hamonangan</p>
            </footer>
        </div>
    </div>
    @stack('scripts') {{-- Tambahkan ini untuk script per halaman --}}
</body>
</html>
 
