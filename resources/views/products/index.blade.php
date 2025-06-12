@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-8 text-center sm:text-left">Daftar Produk Pakaian</h1>

    {{-- Form Pencarian dan Filter --}}
    <form action="{{ route('products.index') }}" method="GET" class="mb-8 bg-white p-4 sm:p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari Produk</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama produk..."
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="sort_price" class="block text-sm font-medium text-gray-700">Urutkan Harga</label>
                <select name="sort_price" id="sort_price"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Default (Terbaru)</option>
                    <option value="lowest" {{ request('sort_price') == 'lowest' ? 'selected' : '' }}>Termurah</option>
                    <option value="highest" {{ request('sort_price') == 'highest' ? 'selected' : '' }}>Termahal</option>
                </select>
            </div>
            <button type="submit" class="sm:col-start-3 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Filter
            </button>
        </div>
    </form>

    @if($products->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <p class="text-gray-600 text-xl">Belum ada produk yang tersedia saat ini.</p>
            <p class="text-gray-500 mt-2">Silakan cek kembali nanti!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover object-center transition-transform duration-300 group-hover:scale-105">
                    @else
                         <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">Tidak Ada Gambar</div>
                    @endif
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-800 truncate group-hover:text-indigo-600 transition-colors duration-200">{{ $product->name }}</h3>
                        <p class="text-lg text-gray-700 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if($product->size)
                        <p class="text-sm text-gray-600 mt-1">Ukuran: {{ $product->size }}</p>
                        @endif
           
                        <a href="{{ route('products.show', $product->id) }}" class="w-full inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out transform group-hover:bg-indigo-700">
                            Lihat Detail
                        </a>
                        {{-- Tombol Tambah ke Keranjang akan ditambahkan di show.blade.php atau di sini jika diinginkan --}}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-10">
            {{ $products->links() }} {{-- Paginasi akan memerlukan styling tambahan jika menggunakan default Laravel --}}
        </div>
    @endif
</div>
@endsection