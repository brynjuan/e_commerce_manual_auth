@extends('layouts.admin_app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Manajemen Pesanan</h2>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <p class="text-gray-600">Belum ada pesanan.</p>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pesanan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                <a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name ?? 'Pengguna Dihapus' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                    @if($order->status == 'shipped') bg-purple-100 text-purple-800 @endif
                                    @if($order->status == 'delivered') bg-green-100 text-green-800 @endif
                                    @if($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                                    @if($order->status == 'waiting_payment_verification') bg-orange-100 text-orange-800 @endif
                                ">
                                    {{ ucfirst($order->status) }}
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection