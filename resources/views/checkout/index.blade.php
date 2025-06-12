@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Checkout</h1>
        <a href="{{ route('cart.index') }}" class="mt-4 sm:mt-0 text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-150">
            &larr; Kembali ke Keranjang
        </a>
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Informasi Pengiriman & Pembayaran</h3>
            <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman Lengkap</label>
                    <textarea id="shipping_address" name="shipping_address" rows="4" required
                              class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">{{ old('shipping_address', $user->shipping_address ?? '') }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">Pastikan alamat sudah benar dan lengkap untuk kemudahan pengiriman.</p>
                </div>
                <hr>
                <h4 class="text-xl font-semibold text-gray-700 mb-1 pt-2">Metode Pembayaran</h4>
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Pilih Metode Pembayaran</label>
                    <select id="payment_method" name="payment_method" required
                            class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                        <option value="">-- Pilih Metode --</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                        <option value="epayment" {{ old('payment_method') == 'epayment' ? 'selected' : '' }}>E-Payment (Transfer/QRIS)</option>
                    </select>
                </div>

                {{-- Informasi Pembayaran E-Payment (QRIS & No Rekening) --}}
                <div id="epayment-info-section" class="hidden mt-4 p-4 border border-blue-200 rounded-lg bg-blue-50">
                    <h5 class="text-md font-semibold text-blue-700 mb-3">Informasi Pembayaran E-Payment</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-700 mb-1"><strong>Scan QRIS:</strong></p>
                            <img src="{{ asset('images/qris_example.png') }}" alt="QRIS Pembayaran" class="w-40 h-40 object-contain border rounded-md">
                            <p class="text-xs text-gray-500 mt-1">Gunakan aplikasi E-Wallet Anda untuk scan QRIS di atas.</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-700 mb-1"><strong>Atau Transfer ke Nomor Dana Berikut:</strong></p>
                            <p class="text-md font-semibold text-gray-800">DANA: <span class="text-indigo-600">0852-3240-8053</span></p>
                            <p class="text-sm text-gray-600">Atas Nama: <span class="font-medium">HMTI SHOP</span></p>
                            <p class="text-xs text-gray-500 mt-1">Pastikan untuk mentransfer sesuai total pembayaran.</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-blue-600">Setelah melakukan pembayaran, silakan unggah bukti transfer Anda di bawah ini.</p>
                </div>


                <div>
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran</label>
                    <input id="payment_proof" type="file" name="payment_proof" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 transition duration-150 ease-in-out cursor-pointer {{ $errors->has('payment_proof') ? 'border-red-500' : 'border-gray-300' }}">
                    <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, GIF, SVG. Maks: 2MB.</p>
                    @error('payment_proof') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div id="admin-verifier-section"> {{-- Selalu ditampilkan karena admin verifikator sekarang wajib --}}
                    <label for="verified_by_admin_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Admin Verifikator</label>
                    <select id="verified_by_admin_id" name="verified_by_admin_id"
                            class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                        <option value="">-- Pilih Admin --</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ old('verified_by_admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                    @error('verified_by_admin_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-lg text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 ease-in-out transform hover:scale-105">
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-1 bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Ringkasan Pesanan</h3>
            <div class="space-y-4">
                @foreach ($cartItems as $item)
                    @if($item->productSize && $item->productSize->product) {{-- Pastikan relasi ada --}}
                        @php
                            $product = $item->productSize->product;
                            $productSize = $item->productSize;
                        @endphp
                    <div class="flex justify-between items-center text-sm">
                        <div>
                            <p class="font-medium text-gray-800">{{ $product->name }}</p>
                            @if($productSize->size)
                                <p class="text-xs text-gray-500">Ukuran: {{ $productSize->size }}</p>
                            @endif
                            <p class="text-gray-500">Jumlah: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-gray-700">Rp {{ number_format($product->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                    @else
                    <p class="text-sm text-red-500 italic">Item tidak valid dalam keranjang.</p>
                    @endif
                @endforeach
            </div>
            <hr class="my-6 border-gray-300">
            <div class="flex justify-between items-center text-lg">
                <p class="font-semibold text-gray-800">Total Pembayaran:</p>
                <p class="font-bold text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentMethodSelect = document.getElementById('payment_method');
    const adminVerifierSection = document.getElementById('admin-verifier-section');
    const epaymentInfoSection = document.getElementById('epayment-info-section');
    const adminSelect = document.getElementById('verified_by_admin_id');

    function toggleAdminVerifier() {
        // Admin verifier section is now always visible and required if a payment method is selected
        if (paymentMethodSelect.value === 'cash' || paymentMethodSelect.value === 'epayment') {
            adminVerifierSection.classList.remove('hidden');
            adminSelect.required = true;
        } else {
            // If no payment method is selected, or an invalid one, hide and make not required (though validation should catch invalid)
            adminVerifierSection.classList.add('hidden'); // Sebaiknya tetap ada untuk konsistensi jika value kosong
            adminSelect.required = false;
        }

        // Toggle e-payment info section
        if (paymentMethodSelect.value === 'epayment') {
            epaymentInfoSection.classList.remove('hidden');
        } else {
            epaymentInfoSection.classList.add('hidden');
        }
    }
    paymentMethodSelect.addEventListener('change', toggleAdminVerifier);
    toggleAdminVerifier(); // Panggil saat load untuk inisialisasi
});
</script>
@endpush