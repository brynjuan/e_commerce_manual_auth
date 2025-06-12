<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request
    {
        $query = Auth::user()->orders();

        if ($request->filled('status_filter')) {
            if ($request->status_filter === 'waiting_verification') {
                $query->where('status', 'waiting_payment_verification')
                      ->where('payment_status', 'waiting_verification');
            }
            // Anda bisa menambahkan filter lain di sini jika perlu
            // elseif ($request->status_filter === 'paid') {
            //     $query->where('payment_status', 'paid');
            // }
        }
        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan pesanan milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        // Load item pesanan, ProductSize terkait, dan Product utama melalui ProductSize.
        // Juga muat relasi product langsung pada item jika masih ingin digunakan untuk beberapa info.
        $order->load(['items.product', 'items.productSize.product', 'user']);

        return view('orders.show', compact('order'));
    }

        /**
     * Display the invoice for a specific order.
     */
    public function showInvoice(Order $order)
    {
        // Pastikan pesanan milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        // Load relasi yang dibutuhkan, termasuk productSize untuk detail ukuran
        $order->load(['user', 'items.product', 'items.productSize.product']);

        return view('orders.invoice', compact('order'));
    }

}