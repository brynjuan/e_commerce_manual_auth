@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-8">Riwayat Pesanan Anda</h1>

    {{-- Pesan Flash --}}
    @include('partials._flash_messages')

    @if($orders->isEmpty())
        <div class="bg-white p-8 rounded-xl shadow-lg text-center">
            <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <h2 class="text-2xl font-semibold text-gray-700 mb-3">Anda belum memiliki riwayat pesanan.</h2>
            <p class="text-gray-500 mb-6">Semua pesanan yang Anda buat akan muncul di sini.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform hover:scale-105">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pesanan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors duration-150">
                                <a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                    @if($order->status == 'shipped') bg-purple-100 text-purple-800 @endif
                                    @if($order->status == 'delivered') bg-green-100 text-green-800 @endif
                                    @if($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                                    @if($order->status == 'waiting_payment_verification') bg-orange-100 text-orange-800 @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->payment_status == 'unpaid') bg-gray-100 text-gray-800 @endif
                                    @if($order->payment_status == 'waiting_verification') bg-yellow-100 text-yellow-800 @endif
                                    @if($order->payment_status == 'paid') bg-green-100 text-green-800 @endif
                                    @if($order->payment_status == 'failed') bg-red-100 text-red-800 @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                                @if($order->payment_status == 'waiting_verification')
                                    <a href="{{ route('checkout.waiting_verification', $order) }}" class="ml-2 text-xs text-blue-600 hover:text-blue-800">(Lihat Instruksi)</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors duration-150 mr-3">Lihat Detail</a>
                                <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="text-green-600 hover:text-green-800 font-semibold transition-colors duration-150">Lihat Nota</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection