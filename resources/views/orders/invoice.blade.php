<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pesanan #{{ $order->id }}</title>
    <!-- Tailwind CSS CDN (Opsional, jika ingin menggunakan beberapa utilitas Tailwind di sini) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            /* Ganti dengan font-family dari Tailwind jika Anda menyertakan CDN di atas */
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 2.5em;
            color: #333;
        }
        .invoice-header p {
            margin: 5px 0 0;
            font-size: 0.9em;
            color: #555;
        }
        .customer-details, .order-details {
            margin-bottom: 20px;
        }
        .customer-details h3, .order-details h3, .items-table h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            font-size: 1.2em;
        }
        .items-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f9f9f9;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
            font-size: 1.1em;
        }
        .total-section strong {
            font-size: 1.2em;
        }
        .footer-note {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }
        @media print {
            body { background-color: #fff; margin: 0; padding: 0; }
            .invoice-container { box-shadow: none; border: none; margin: 0 auto; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>NOTA PEMBELIAN</h1>
            <p>Toko Pakaian Anda</p> {{-- Ganti dengan nama toko Anda --}}
            {{-- <p>Alamat Toko Anda</p> --}}
            {{-- <p>Kontak Toko Anda</p> --}}
        </div>

        <div class="order-details">
            <h3>Detail Pesanan</h3>
            <p><strong>Nomor Pesanan:</strong> #{{ $order->id }}</p>
            <p><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d F Y, H:i') }}</p>
            <p><strong>Status Pesanan:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <div class="customer-details">
            <h3>Informasi Pelanggan</h3>
            <p><strong>Nama:</strong> {{ $order->user->name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Alamat Pengiriman:</strong><br>{{ nl2br(e($order->shipping_address)) }}</p>
        </div>

        <div class="items-table">
            <h3>Item yang Dibeli</h3>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product->name ?? 'Produk Tidak Tersedia' }}
                            @if($item->productSize)
                                <br><span style="font-size: 0.8em; color: #555;">Ukuran: {{ $item->productSize->size }}</span>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <p><strong>Total Pembayaran: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></p>
        </div>

        <div class="footer-note">
            <p>Terima kasih telah berbelanja di Toko Pakaian Kami!</p>
            <button onclick="window.print()" class="no-print mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">Cetak Nota</button>
        </div>
    </div>
</body>
</html>