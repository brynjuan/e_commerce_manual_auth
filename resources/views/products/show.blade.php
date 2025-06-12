@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-150">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            Kembali ke Daftar Produk
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-xl overflow-hidden lg:flex">
        <div class="md:w-1/2">
            @if($product->image_url && $product->image_url !== asset('images/placeholder.png'))
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-64 md:h-96 lg:h-full object-cover">
            @else
                <div class="w-full h-64 md:h-96 lg:h-full bg-gray-200 flex items-center justify-center text-gray-500 text-lg">Tidak Ada Gambar</div>
            @endif
        </div>
        <div class="lg:w-1/2 p-6 sm:p-8">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>
            <p class="text-3xl text-indigo-600 font-semibold mb-6">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <div class="prose prose-sm sm:prose lg:prose-lg max-w-none text-gray-600 mb-8">
                <h4 class="text-xl font-semibold text-gray-700 mb-2">Deskripsi Produk:</h4>
                <p>{{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}</p>
            </div>

            @php
                $availableProductSizes = $product->sizes->filter(function ($size) {
                    return $size->stock > 0;
                });
                $totalStock = $product->sizes->sum('stock');
            @endphp

            {{-- Form Tambah ke Keranjang dengan Pilihan Ukuran --}}
            <form action="{{ route('cart.store') }}" method="POST" class="mt-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                @if($availableProductSizes->isNotEmpty())
                    <div class="mb-6">
                        <label class="block text-md font-medium text-gray-700 mb-2">Pilih Ukuran:</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($availableProductSizes as $productSize)
                                <div class="relative">
                                    <input type="radio" name="product_size_id" id="size_{{ $productSize->id }}" value="{{ $productSize->id }}" 
                                           data-stock="{{ $productSize->stock }}"
                                           class="sr-only peer size-selector" required>
                                    <label for="size_{{ $productSize->id }}" 
                                           class="px-4 py-2 border border-gray-300 rounded-lg cursor-pointer text-sm font-medium 
                                                  hover:bg-indigo-50 hover:border-indigo-400 
                                                  peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600
                                                  transition-all duration-200 ease-in-out">
                                        {{ $productSize->size }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('product_size_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <p class="text-gray-700"><span class="font-semibold">Stok Tersedia untuk Ukuran Dipilih:</span> <span id="selected_size_stock_display">Pilih ukuran</span></p>
                    </div>

                    <div class="flex items-center space-x-4 mb-4">
                        <label for="quantity" class="text-md font-medium text-gray-700">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" 
                               class="w-24 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out"
                               disabled> {{-- Disabled until size is selected --}}
                    </div>
                    @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                    <button type="submit" id="add-to-cart-button" disabled
                            class="flex-1 sm:flex-none px-6 py-3 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all duration-200 ease-in-out transform hover:scale-105">
                        Tambah ke Keranjang
                    </button>
                @else
                    <p class="text-red-600 text-sm mt-3 font-medium">Maaf, stok produk ini sedang habis.</p>
                     <button type="button" disabled
                            class="flex-1 sm:flex-none px-6 py-3 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-gray-400 cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sizeSelectors = document.querySelectorAll('.size-selector');
    const quantityInput = document.getElementById('quantity');
    const stockDisplay = document.getElementById('selected_size_stock_display');
    const addToCartButton = document.getElementById('add-to-cart-button');

    sizeSelectors.forEach(selector => {
        selector.addEventListener('change', function () {
            if (this.checked) {
                const stock = parseInt(this.dataset.stock);
                stockDisplay.textContent = stock > 0 ? stock : 'Habis';
                quantityInput.max = stock;
                quantityInput.disabled = stock <= 0;
                addToCartButton.disabled = stock <= 0;
                if (quantityInput.value > stock) {
                    quantityInput.value = stock > 0 ? 1 : 0;
                }
            }
        });
    });

    // Initialize: if only one size and it's selected by default (e.g. if you add 'checked' in blade)
    const checkedSize = document.querySelector('.size-selector:checked');
    if (checkedSize) {
        checkedSize.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush