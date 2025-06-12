{{-- Partial view untuk form produk (digunakan di create dan edit) --}}

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-md" role="alert">
        <p class="font-bold">Oops! Ada beberapa masalah dengan input Anda:</p>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-8"> {{-- Menambah jarak antar grup form --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
               class="block w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea id="description" name="description" rows="4"
                  class="block w-full px-4 py-2 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
        <input id="price" type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required min="0" step="0.01"
               class="block w-full px-4 py-2 border {{ $errors->has('price') ? 'border-red-500' : 'border-gray-300' }} rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
        @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
        <input id="image" type="file" name="image"
               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 transition duration-150 ease-in-out cursor-pointer {{ $errors->has('image') ? 'border-red-500' : 'border-gray-300' }}">
        @if(isset($product) && $product->image_url && $product->image_url !== asset('images/placeholder.png'))
            <p class="mt-3 text-sm text-gray-600">Gambar saat ini:                         <img src="{{ asset('storage/' . $product->image_path) }}" alt="Gambar Produk {{ $product->name ?? '' }}" class="h-20 w-20 rounded-lg object-cover inline-block ml-2 shadow"></p>
        @endif
        @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    {{-- Bagian untuk Varian Ukuran dan Stok --}}
    <div class="border-t border-gray-200 pt-6 mt-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-1">Varian Ukuran dan Stok</h3>
        <p class="text-sm text-gray-500 mb-4">Tambahkan ukuran dan stok yang tersedia untuk produk ini. Setiap ukuran harus unik per produk.</p>
        @error('variants') <div class="text-red-500 text-sm mb-2">{{ $message }}</div> @enderror
        <div id="variants-container" class="space-y-4">
            @php
                $currentVariants = [];
                if (old('variants')) {
                    $currentVariants = old('variants');
                } elseif (isset($product) && $product->sizes && $product->sizes->isNotEmpty()) {
                    $currentVariants = $product->sizes->map(function($size) {
                        return ['id' => $size->id, 'size' => $size->size, 'stock' => $size->stock];
                    })->toArray();
                } elseif (!isset($product) || (isset($product) && (!isset($product->sizes) || $product->sizes->isEmpty()))) {
                    // Default ke satu baris kosong untuk form create, atau untuk edit jika produk tidak punya varian dan tidak ada input lama
                    $currentVariants = [['id' => '', 'size' => '', 'stock' => '']];
                }
            @endphp

            @foreach($currentVariants as $key => $variant)
                <div class="variant-row flex items-center space-x-2 mb-3 p-3 border rounded-md bg-gray-50 shadow-sm">
                    <input type="hidden" name="variants[{{ $key }}][id]" value="{{ $variant['id'] ?? '' }}">
                    <div class="flex-1">
                        <label for="variants_{{ $key }}_size" class="block text-xs font-medium text-gray-700">Ukuran</label>
                        <select id="variants_{{ $key }}_size" name="variants[{{ $key }}][size]" class="mt-1 block w-full py-2 px-3 border {{ $errors->has('variants.'.$key.'.size') ? 'border-red-500' : 'border-gray-300' }} bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Ukuran</option>
                            @foreach($allRegisteredSizes as $s)
                                <option value="{{ $s }}" {{ ($variant['size'] ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error("variants.{$key}.size") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-1">
                        <label for="variants_{{ $key }}_stock" class="block text-xs font-medium text-gray-700">Stok</label>
                        <input id="variants_{{ $key }}_stock" type="number" name="variants[{{ $key }}][stock]" value="{{ $variant['stock'] ?? 0 }}" placeholder="Stok" class="mt-1 block w-full py-2 px-3 border {{ $errors->has('variants.'.$key.'.stock') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0">
                        @error("variants.{$key}.stock") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="pt-5">
                        <button type="button" class="remove-variant-btn inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Hapus Varian">
                            <svg class="w-4 h-4 pointer-events-none" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-variant-btn" class="mt-4 inline-flex items-center px-4 py-2 border border-dashed border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Varian
        </button>
    </div>

    <div class="pt-6"> {{-- Menambah sedikit padding atas untuk tombol --}}
        <button type="submit"
                class="inline-flex justify-center py-3 px-6 border border-transparent shadow-md text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out transform hover:scale-105">
            {{ isset($product) && $product->id ? 'Perbarui Produk' : 'Tambah Produk' }}
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const variantsContainer = document.getElementById('variants-container');
    const addVariantBtn = document.getElementById('add-variant-btn');
    let variantIndex = {{ count($currentVariants) }}; // Inisialisasi berdasarkan jumlah varian yang sudah ada

    const createVariantRowHTML = (index, data = {}) => {
        let optionsHTML = '<option value="">Pilih Ukuran</option>';
        @foreach($allRegisteredSizes as $s)
            optionsHTML += `<option value="{{ $s }}" ${ (data.size === '{{ $s }}') ? 'selected' : '' } >{{ $s }}</option>`;
        @endforeach

        return `
            <input type="hidden" name="variants[${index}][id]" value="${data.id || ''}">
            <div class="flex-1">
                <label for="variants_${index}_size" class="block text-xs font-medium text-gray-700">Ukuran</label>
                <select id="variants_${index}_size" name="variants[${index}][size]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    ${optionsHTML}
                </select>
            </div>
            <div class="flex-1">
                <label for="variants_${index}_stock" class="block text-xs font-medium text-gray-700">Stok</label>
                <input id="variants_${index}_stock" type="number" name="variants[${index}][stock]" value="${data.stock || 0}" placeholder="Stok" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0">
            </div>
            <div class="pt-5">
                <button type="button" class="remove-variant-btn inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Hapus Varian">
                    <svg class="w-4 h-4 pointer-events-none" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
        `;
    };

    addVariantBtn.addEventListener('click', function () {
        const div = document.createElement('div');
        div.className = 'variant-row flex items-center space-x-2 mb-3 p-3 border rounded-md bg-gray-50 shadow-sm animate-fadeIn';
        div.innerHTML = createVariantRowHTML(variantIndex);
        variantsContainer.appendChild(div);
        variantIndex++;
    });

    variantsContainer.addEventListener('click', function (e) {
        const removeButton = e.target.closest('.remove-variant-btn');
        if (removeButton) {
            removeButton.closest('.variant-row').remove();
        }
    });

    // Tambahkan style untuk animasi fadeIn jika belum ada
    if (!document.getElementById('variant-animation-style')) {
        const style = document.createElement('style');
        style.id = 'variant-animation-style';
        style.innerHTML = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out;
            }
        `;
        document.head.appendChild(style);
    }
});
</script>
@endpush
