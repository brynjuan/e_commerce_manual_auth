@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white p-8 sm:p-10 rounded-xl shadow-xl text-center max-w-2xl mx-auto">
        <svg class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">Pesanan Berhasil Dibuat!</h1>

        <p class="text-lg text-gray-600 mb-3">Terima kasih telah berbelanja. Pesanan Anda dengan nomor <strong class="text-indigo-600 font-semibold">#{{ $order->id }}</strong> telah berhasil kami terima.</p>
        <p class="text-md text-gray-600 mb-6">Kami akan segera memproses pesanan Anda. Berikut adalah ringkasan pesanan Anda:</p>

        {{-- Ringkasan Item Pesanan --}}
        <div class="border-t border-b border-gray-200 my-6 py-6 text-left">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Item yang Dipesan:</h3>
            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex justify-between items-start">
                        <div class="flex-grow pr-4"> {{-- pr-4 to add some space before price --}}
                            @if($item->productSize && $item->productSize->product)
                                <p class="font-medium text-gray-800">{{ $item->productSize->product->name }}</p>
                                <p class="text-xs text-gray-500">Ukuran: {{ $item->productSize->size }}</p>
                            @elseif($item->product) {{-- Fallback if productSize is not available but product is --}}
                                <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                            @else
                                <p class="font-medium text-gray-500 italic">Produk Tidak Ditemukan</p>
                            @endif
                            <p class="text-sm text-gray-500">Jumlah: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-sm text-gray-700 ml-4">Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 pt-4 border-t border-gray-300 text-right">
                <p class="text-lg font-semibold text-gray-800">Total Pesanan: <span class="text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
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