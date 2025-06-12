@extends('layouts.app')

@section('title', 'Menunggu Verifikasi Pembayaran')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white p-8 sm:p-10 rounded-xl shadow-xl text-center max-w-2xl mx-auto">
        <svg class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">Menunggu Verifikasi Pembayaran</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <p class="text-lg text-gray-600 mb-3">Pesanan Anda dengan nomor <strong class="text-indigo-600 font-semibold">#{{ $order->id }}</strong> sedang menunggu verifikasi pembayaran oleh admin.</p>
        <p class="text-md text-gray-600 mb-6">Anda akan menerima notifikasi atau dapat memeriksa status pesanan Anda secara berkala di halaman riwayat pesanan.</p>
        <p class="text-md text-gray-600 mb-6">Silakan refresh halaman ini atau cek halaman riwayat pesanan untuk melihat status terbaru.</p>


        <div class="mt-6 pt-4 border-t border-gray-300">
            <p class="text-sm text-gray-500">Metode Pembayaran: <span class="font-semibold">{{ ucfirst($order->payment_method) }}</span></p>
            @if($order->payment_method === 'cash' && $order->verifier)
            <p class="text-sm text-gray-500">Admin Verifikator: <span class="font-semibold">{{ $order->verifier->name }}</span></p>
            @endif
            <p class="text-lg font-semibold text-gray-800 mt-2">Total Pesanan: <span class="text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('orders.show', $order) }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform hover:scale-105">
                Lihat Detail Pesanan
            </a>
            <a href="{{ route('products.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform hover:scale-105">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
