@extends('layouts.admin_app')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Detail Pesanan <span class="text-indigo-600">#{{ $order->id }}</span></h2>
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800">&larr; Kembali ke Daftar Pesanan</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Informasi Pesanan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Pelanggan</p>
                <p class="text-md font-medium text-gray-900">{{ $order->user->name ?? 'Pengguna Dihapus' }} ({{ $order->user->email ?? '-' }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                <p class="text-md font-medium text-gray-900">{{ $order->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status Pesanan</p>
                <p class="text-md font-medium text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                        @if($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                        @if($order->status == 'shipped') bg-purple-100 text-purple-800 @endif
                        @if($order->status == 'delivered') bg-green-100 text-green-800 @endif
                        @if($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                        @if($order->status == 'waiting_payment_verification') bg-orange-100 text-orange-800 @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Metode Pembayaran</p>
                <p class="text-md font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status Pembayaran</p>
                <p class="text-md font-medium text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($order->payment_status == 'unpaid') bg-gray-100 text-gray-800 @endif
                        @if($order->payment_status == 'waiting_verification') bg-yellow-100 text-yellow-800 @endif
                        @if($order->payment_status == 'paid') bg-green-100 text-green-800 @endif
                        @if($order->payment_status == 'failed') bg-red-100 text-red-800 @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                </p>
            </div>
            @if($order->payment_method === 'cash' && $order->verified_by_admin_id)
                <div>
                    <p class="text-sm text-gray-500">Admin Penerima Tunai (Dipilih Pengguna)</p>
                    <p class="text-md font-medium text-gray-900">{{ $order->verifier->name ?? 'Tidak diketahui' }}</p>
                </div>
            @endif
             <div>
                <p class="text-sm text-gray-500">Total Pembayaran</p>
                <p class="text-md font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Alamat Pengiriman</p>
                <p class="text-md font-medium text-gray-900">{{ $order->shipping_address }}</p>
            </div>
        </div>

        {{-- Form untuk update status pesanan --}}
        @if($order->payment_status === 'paid') {{-- Hanya tampilkan jika pembayaran sudah lunas --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h4 class="text-lg font-semibold text-gray-700 mb-2">Ubah Status Pesanan</h4>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-center space-x-3">
                @csrf
                @method('PUT')
                <label for="status" class="sr-only">Ubah Status:</label>
                <select name="status" id="status" class="block w-full max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    {{-- <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option> --}}
                    {{-- <option value="waiting_payment_verification" {{ $order->status == 'waiting_payment_verification' ? 'selected' : '' }}>Menunggu Verifikasi Pembayaran</option> --}}
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Status Pesanan
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Item Pesanan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if($item->productSize && $item->productSize->product)
                                    {{ $item->productSize->product->name }}
                                    <div class="text-xs text-gray-500">Ukuran: {{ $item->productSize->size }}</div>
                                @elseif($item->product) {{-- Fallback --}}
                                    {{ $item->product->name }}
                                    @if($item->product->size) {{-- Fallback untuk size lama jika ada --}}
                                        <div class="text-xs text-gray-500">Ukuran: {{ $item->product->size }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Produk Dihapus</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase tracking-wider">Total Keseluruhan:</td>
                        <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($order->payment_status === 'waiting_verification')
    <div class="mt-8 bg-white p-6 sm:p-8 rounded-xl shadow-lg">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Verifikasi Pembayaran</h3>
        @if($order->payment_proof_path)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran:</p>
               <a href="{{ Storage::disk('public')->url($order->payment_proof_path) }}" target="_blank" class="block">
                    <img src="{{ asset('storage/' . $order->payment_proof_path) }}" alt="Bukti Pembayaran" class="max-w-sm h-auto rounded-md shadow-md border hover:opacity-80 transition-opacity">
                </a>
            </div>
        @else
            <p class="text-gray-500 mb-4">Tidak ada bukti pembayaran yang diunggah.</p>
        @endif

        @if($order->payment_method === 'cash' && $order->verified_by_admin_id && $order->verifier)
            <p class="text-sm text-gray-600 mb-4">Pembayaran tunai ini ditujukan kepada admin: <span class="font-semibold">{{ $order->verifier->name }}</span>.</p>
        @endif

        <form action="{{ route('admin.orders.verify_payment', $order) }}" method="POST" class="flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0">
            @csrf
            <button type="submit" name="payment_action" value="approve"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Setujui Pembayaran
            </button>
            <button type="submit" name="payment_action" value="reject"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Tolak Pembayaran
            </button>
        </form>
    </div>
    @endif
</div>
@endsection