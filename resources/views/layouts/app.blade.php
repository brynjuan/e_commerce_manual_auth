<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 antialiased flex flex-col min-h-screen">
    <div id="app" class="flex-grow">
        <nav x-data="{ open: false }" class="bg-white shadow-lg sticky top-0 z-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center">
                            {{-- Tempatkan logo Anda di sini. Sesuaikan path dan class jika perlu --}}
                            <img class="h-10 w-auto mr-2" src="{{ asset('images/hmti logo.png') }}" alt="StyleNest Logo">
                            <!-- <img class="h-10 w-auto mr-2" src="{{ asset('images/hmti logo.png') }}" alt="StyleNest Logo"> -->
                            <!-- <span class="text-2xl font-bold text-indigo-600 hover:text-indigo-700 transition-colors duration-200">
                                StyleNest -->
                            <span class="text-2xl font-bold text-indigo-600 hover:text-indigo-700 transition-colors duration-200">
                                HMTI SHOP                                
                            </span>
                        </a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 @if(request()->routeIs('products.index')) text-indigo-600 bg-indigo-50 @endif">Produk</a>
                            <a href="{{ route('cart.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 @if(request()->routeIs('cart.index')) text-indigo-600 bg-indigo-50 @endif">Keranjang</a>
                            @auth
                            <a href="{{ route('orders.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 @if(request()->routeIs('orders.index')) text-indigo-600 bg-indigo-50 @endif">Riwayat Pesanan</a>
                            @php
                                $countWaitingOrders = Auth::user()->orders()
                                    ->where('status', 'waiting_payment_verification')
                                    ->where('payment_status', 'waiting_verification')
                                    ->count();
                            @endphp
                            @if($countWaitingOrders > 0)
                                <a href="{{ route('orders.index', ['status_filter' => 'waiting_verification']) }}" 
                                   class="relative px-3 py-2 rounded-md text-sm font-medium text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 transition-all duration-200 @if(request('status_filter') == 'waiting_verification') text-yellow-700 bg-yellow-100 @endif">
                                    Menunggu Verifikasi
                                    <span class="absolute top-0 right-0 -mt-1 -mr-1 px-1.5 py-0.5 bg-red-500 text-white text-xs rounded-full">{{ $countWaitingOrders }}</span>
                                </a>
                            @endif
                            <a href="{{ route('profile.show') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 @if(request()->routeIs('profile.show')) text-indigo-600 bg-indigo-50 @endif">{{ Auth::user()->name }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-red-600 hover:bg-red-50 transition-all duration-200">Logout</a>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 @if(request()->routeIs('login')) text-indigo-600 bg-indigo-50 @endif">Login</a>
                            @if (Route::has('register.show'))
                            <a href="{{ route('register.show') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">Register</a>
                            @endif
                            @endguest
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <button @click="open = !open" type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg :class="{'hidden': open, 'block': !open }" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg :class="{'block': open, 'hidden': !open }" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 @if(request()->routeIs('products.index')) text-indigo-600 bg-indigo-50 @endif">Produk</a>
                    <a href="{{ route('cart.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 @if(request()->routeIs('cart.index')) text-indigo-600 bg-indigo-50 @endif">Keranjang</a>
                    @auth
                    <a href="{{ route('orders.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 @if(request()->routeIs('orders.index')) text-indigo-600 bg-indigo-50 @endif">Riwayat Pesanan</a>
                    {{-- Logika yang sama untuk mobile menu, menggunakan $countWaitingOrders yang sudah dihitung --}}
                    @if($countWaitingOrders > 0)
                        <a href="{{ route('orders.index', ['status_filter' => 'waiting_verification']) }}" 
                           class="relative block px-3 py-2 rounded-md text-base font-medium text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 @if(request('status_filter') == 'waiting_verification') text-yellow-700 bg-yellow-100 @endif">
                            Menunggu Verifikasi
                            <span class="absolute top-0 right-0 -mt-1 mr-1 px-1.5 py-0.5 bg-red-500 text-white text-xs rounded-full">{{ $countWaitingOrders }}</span>
                        </a>
                    @endif
                    <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 @if(request()->routeIs('profile.show')) text-indigo-600 bg-indigo-50 @endif">{{ Auth::user()->name }}</a>
                    <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST"> @csrf </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-600 hover:bg-red-50">Logout</a>
                    @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 @if(request()->routeIs('login')) text-indigo-600 bg-indigo-50 @endif">Login</a>
                    @if (Route::has('register.show'))
                    <a href="{{ route('register.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 @if(request()->routeIs('register.show')) text-indigo-600 bg-indigo-50 @endif">Register</a>
                    @endif
                    @endguest
                </div>
            </div>
        </nav>

        <main class="py-8">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]"> {{-- Adjust min-height as needed --}}
                @yield('content')
            </div>
        </main>
    </div>

    <footer class="bg-gray-800 text-white text-center py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-sm">Bekerja keras agar bisa berbelanja lebih keras!</p>
            <p class="text-xs mt-1 mb-3">F55123030</p>
            
            {{-- Media Sosial --}}
            <div class="mt-4 flex justify-center space-x-6">
                <a href="YOUR_FACEBOOK_LINK" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200" aria-label="StyleNest Facebook">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"> {{-- Ganti dengan SVG ikon Facebook Anda --}}
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="https://www.instagram.com/hmti.shop" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200" aria-label="StyleNest Instagram">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"> {{-- Ganti dengan SVG ikon Instagram Anda --}}
                        <path fill-rule="evenodd" d="M12.315 2.315a8.748 8.748 0 012.82.514c.981.21 1.804.573 2.51.995.71.424 1.283.93 1.773 1.535.491.603.87 1.26.996 1.992.21.72.363 1.48.435 2.287.072.806.093 1.176.093 3.367s-.021 2.561-.093 3.367c-.072.807-.225 1.567-.435 2.287a4.959 4.959 0 01-.996 1.992c-.49.605-1.063 1.111-1.773 1.535-.706.422-1.53.785-2.51.995a8.748 8.748 0 01-2.82.514c-.806.072-1.176.093-3.367.093s-2.561-.021-3.367-.093a8.748 8.748 0 01-2.82-.514 4.959 4.959 0 01-2.51-.995c-.71-.424-1.283-.93-1.773-1.535a4.959 4.959 0 01-.996-1.992c-.21-.72-.363-1.48-.435-2.287-.072-.806-.093-1.176-.093-3.367s.021-2.561.093-3.367c.072-.807.225-1.567.435-2.287.126-.732.506-1.389.996-1.992.49-.605 1.063-1.111 1.773-1.535.706-.422 1.53-.785 2.51-.995a8.748 8.748 0 012.82-.514c.806-.072 1.176-.093 3.367-.093s2.561.021 3.367.093zM12 6.952a5.048 5.048 0 100 10.096 5.048 5.048 0 000-10.096zM12 15a3 3 0 110-6 3 3 0 010 6zm5.083-8.083a1.167 1.167 0 100-2.333 1.167 1.167 0 000 2.333z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="YOUR_TWITTER_LINK" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200" aria-label="StyleNest Twitter">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"> {{-- Ganti dengan SVG ikon Twitter Anda --}}
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                {{-- Tambahkan link media sosial lainnya jika perlu --}}
            </div>
        </div>
    </footer>

    <!-- AlpineJS CDN (untuk menu mobile) -->
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>