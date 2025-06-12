@extends('layouts.admin_app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Dashboard Admin</h1>
    <p class="text-lg text-gray-600 mb-8">Selamat datang kembali, <span class="font-semibold">{{ Auth::user()->name }}</span>! Berikut adalah ringkasan aktivitas toko Anda.</p>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Card Total Produk -->
        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-500 bg-opacity-75 text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Produk</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $totalProducts }}</p>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="mt-4 block text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-300">Lihat Detail Produk &rarr;</a>
        </div>
        <!-- Card Total Pengguna -->
        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-75 text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="mt-4 block text-sm font-medium text-green-600 hover:text-green-500 transition-colors duration-300">Kelola Pengguna &rarr;</a>
        </div>
        <!-- Card Total Pesanan -->
        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 ease-in-out">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75 text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pesanan</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $totalOrders }}</p>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="mt-4 block text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors duration-300">Lihat Semua Pesanan &rarr;</a>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Aksi Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center justify-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-300">
                <svg class="h-10 w-10 text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-md font-medium text-indigo-700">Tambah Produk Baru</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-300">
                <svg class="h-10 w-10 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="text-md font-medium text-blue-700">Kelola Pesanan</span>
            </a>
            {{-- Anda bisa menambahkan lebih banyak aksi cepat di sini --}}
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-300">
                <svg class="h-10 w-10 text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.08-.94-1-1.414M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 0c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                <span class="text-md font-medium text-green-700">Lihat Pengguna</span>
            </a>
        </div>
    </div>

    {{-- Anda bisa menambahkan bagian lain seperti grafik atau daftar pesanan terbaru di sini --}}

</div>
@endsection