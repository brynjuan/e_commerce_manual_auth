@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Detail Pesanan <span class="text-indigo-600">#{{ $order->id }}</span></h1>
        <a href="{{ route('orders.index') }}" class="mt-4 sm:mt-0 inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-150">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            Kembali ke Riwayat Pesanan
        </a>
    </div>

    {{-- Pesan Flash --}}
    @include('partials._flash_messages')

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg mb-8">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Informasi Pesanan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Nomor Pesanan</p>
                <p class="mt-1 text-md text-gray-900 font-semibold">#{{ $order->id }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Tanggal Pesanan</p>
                <p class="mt-1 text-md text-gray-900">{{ $order->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Alamat Pengiriman</p>
                <p class="mt-1 text-md text-gray-900">{{ $order->shipping_address }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status Pesanan</p>
                <p class="mt-1 text-md text-gray-900">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                        @if($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                        @if($order->status == 'shipped') bg-purple-100 text-purple-800 @endif
                        @if($order->status == 'delivered') bg-green-100 text-green-800 @endif
                        @if($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                    ">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pembayaran</p>
                <p class="mt-1 text-xl font-bold text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Item Pesanan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($order->items as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    @if($item->product && $item->product->image_path)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="h-12 w-12 rounded-md object-cover mr-4 shadow">
                                    @elseif($item->product)
                                        <div class="h-12 w-12 rounded-md bg-gray-200 mr-4 flex items-center justify-center text-gray-400 text-xs shadow">No Img</div>
                                    @endif
                                    <div>
                                        @if($item->product)
                                            {{ $item->product->name }}
                                            @if($item->productSize)
                                                <div class="text-xs text-gray-500">Ukuran: {{ $item->productSize->size }}</div>
                                            @endif
                                        @else
                                            <span class="text-gray-400 italic">Produk Dihapus</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="w-full sm:w-auto inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform hover:scale-105 text-center">
            Cetak Nota
        </a>
        <a href="{{ route('products.index') }}" class="w-full sm:w-auto inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform hover:scale-105 text-center">Lanjutkan Belanja</a>
    </div>
</div>
@endsection