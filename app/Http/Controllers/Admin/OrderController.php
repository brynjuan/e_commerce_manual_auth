<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class OrderController extends Controller
{
    /**
     * Display a listing of the orders for admin.
     */
    public function index()
    {
        // Tampilkan pesanan yang menunggu verifikasi pembayaran di atas atau filter khusus
        $orders = Order::with(['user', 'verifier']) // Muat juga relasi verifier
                        ->orderByRaw("FIELD(payment_status, 'waiting_verification') DESC") // Prioritaskan yang waiting_verification
                        ->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order for admin.
     */
    public function show(Order $order)
    {
        // Muat relasi yang diperlukan, termasuk productSize untuk detail ukuran dan verifier
        $order->load(['user', 'items.productSize.product', 'items.product', 'verifier']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,waiting_payment_verification', // waiting_payment_verification ditambahkan jika admin perlu set manual
        ]);

        // Cegah perubahan status yang seharusnya dikelola oleh verifyPayment
        if (in_array($order->status, ['waiting_payment_verification']) && $request->status !== 'cancelled') {
            if ($order->payment_status === 'waiting_verification' && !in_array($request->status, ['waiting_payment_verification', 'cancelled'])) {
                 return redirect()->route('admin.orders.show', $order)->with('error', 'Status pesanan ini harus diverifikasi pembayarannya terlebih dahulu atau dibatalkan.');
            }
        }
        // Jangan biarkan admin mengubah status ke 'paid' atau 'waiting_verification' dari sini untuk payment_status
        // Status pesanan 'processing' atau 'pending' setelah 'paid' adalah wajar.

        $order->status = $request->status;
        $order->save();
        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function verifyPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_action' => 'required|in:approve,reject'
        ]);

        if ($request->payment_action === 'approve') {
            $order->payment_status = 'paid';
            $order->status = 'processing'; // Status pesanan setelah pembayaran diterima
            $order->verified_by_admin_id = Auth::id(); // Catat admin yang memverifikasi
            $order->save();
            return redirect()->route('admin.orders.show', $order)->with('success', 'Pembayaran berhasil diverifikasi dan status pesanan diperbarui menjadi "Processing".');
        } elseif ($request->payment_action === 'reject') {
            $order->payment_status = 'failed';
            $order->status = 'cancelled'; // Atau status spesifik seperti 'payment_failed' jika ada
            $order->verified_by_admin_id = Auth::id();
            $order->save();
            // Pertimbangkan untuk mengirim notifikasi ke pengguna
            return redirect()->route('admin.orders.show', $order)->with('success', 'Pembayaran ditolak dan status pesanan diperbarui menjadi "Cancelled".');
        }
        return redirect()->route('admin.orders.show', $order)->with('error', 'Aksi tidak valid.');
    }
}