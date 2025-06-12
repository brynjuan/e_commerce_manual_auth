@extends('layouts.admin_app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Manajemen Produk</h2>
        <a href="{{ route('admin.products.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md">
            Tambah Produk Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <p class="text-gray-600">Belum ada produk.</p>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Varian (Ukuran: Stok)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->image_url && $product->image_url !== asset('images/placeholder.png')) {{-- Menggunakan accessor image_url --}}
                                                            <img src="{{ asset('storage/' . $product->image_path) }}"" alt="{{ $product->name }}" class="h-10 w-10 rounded-md object-cover">
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->sizes->sum('stock') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($product->sizes->isNotEmpty())
                                    @foreach($product->sizes as $sizeVariant)
                                        <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-semibold text-gray-700 mr-1 mb-1">{{ $sizeVariant->size }}: {{ $sizeVariant->stock }}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection