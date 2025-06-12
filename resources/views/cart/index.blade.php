@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-8">Keranjang Belanja Anda</h1>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-md" role="alert">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="bg-white p-8 rounded-xl shadow-lg text-center">
            <svg class="w-16 h-16 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <h2 class="text-2xl font-semibold text-gray-700 mb-3">Keranjang belanja Anda kosong.</h2>
            <p class="text-gray-500 mb-6">Sepertinya Anda belum menambahkan produk apapun ke keranjang.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform hover:scale-105">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $total = 0; @endphp
                    @foreach ($cartItems as $item)
                        @if($item->productSize && $item->productSize->product) {{-- Pastikan relasi ada --}}
                            @php
                                $product = $item->productSize->product; // Produk utama dari ProductSize
                                $productSize = $item->productSize; // Varian ukuran
                                $subtotal = $product->price * $item->quantity;
                                $total += $subtotal;
                            @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->image_url && $product->image_url !== asset('images/placeholder.png'))
                                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-md object-cover mr-4 shadow">
                                    @else
                                        <div class="h-12 w-12 rounded-md bg-gray-200 mr-4 flex items-center justify-center text-gray-400 text-xs shadow">No Img</div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        @if($productSize->size)
                                            <div class="text-xs text-gray-500">Ukuran: {{ $productSize->size }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $productSize->stock }}"
                                           class="w-20 text-center px-2 py-1.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                                    <button type="submit" class="ml-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                        Update
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <form action="{{ route('cart.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition-colors duration-150">
                                        <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @else
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center italic">Produk atau varian ukuran tidak tersedia.</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8 bg-white p-6 rounded-xl shadow-lg flex flex-col sm:flex-row justify-between items-center">
            <div class="text-xl font-semibold text-gray-800 mb-4 sm:mb-0">
                Total Belanja: <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout.index') }}" class="w-full sm:w-auto inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform hover:scale-105 text-center">
                Lanjutkan ke Checkout
            </a>
        </div>
    @endif
</div>
@endsection