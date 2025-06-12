<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSize; // Tambahkan ini
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Eager load productSize dan product utama melalui productSize
        $cartItems = $user->cartItems()->with(['productSize.product', 'product'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Hitung total berdasarkan harga produk utama
        $total = $cartItems->sum(function($item) {
            // Pastikan productSize dan product di dalamnya ada
            return ($item->productSize && $item->productSize->product) ? ($item->productSize->product->price * $item->quantity) : 0;
        });

        $admins = User::where('role', 'admin')->get(); // Ambil semua admin

        return view('checkout.index', compact('cartItems', 'user', 'total', 'admins'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        // Eager load productSize dan product utama melalui productSize
        $cartItems = $user->cartItems()->with(['productSize.product'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cash,epayment',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi bukti pembayaran
            'verified_by_admin_id' => 'required_if:payment_method,cash|nullable|exists:users,id', // Wajib jika tunai, admin harus ada            
            // Tambahkan validasi lain jika ada input tambahan di form checkout
        ]);

        // Gunakan transaksi database untuk memastikan semua operasi berhasil atau tidak sama sekali
        DB::beginTransaction();

        try {
            // Hitung total dan cek stok
            $total = 0;
            // Validasi stok sebelum membuat pesanan
            foreach ($cartItems as $item) {
                if (!$item->productSize || !$item->productSize->product) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Produk dalam keranjang tidak valid.');
                }
                if ($item->quantity > $item->productSize->stock) {
                    DB::rollBack(); // Rollback jika stok tidak cukup
                    return redirect()->route('cart.index')->with('error', 'Stok produk "' . $item->productSize->product->name . '" ukuran ' . $item->productSize->size . ' tidak mencukupi.');
                }
                $total += $item->productSize->product->price * $item->quantity;
            }

            // Buat entri pesanan
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'status' => 'waiting_payment_verification', // Status awal pesanan
                'payment_method' => $request->payment_method,
                'payment_status' => 'waiting_verification', // Status awal pembayaran
                'verified_by_admin_id' => $request->payment_method === 'cash' ? $request->verified_by_admin_id : null,
            ]);
            // Simpan bukti pembayaran
            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('payment_proofs', 'public');
                $order->payment_proof_path = $path;
                $order->save();
            }
            // Buat entri item pesanan dan kurangi stok
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id, // Tetap simpan product_id utama
                    'product_size_id' => $item->product_size_id, // Simpan product_size_id
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->productSize->product->price, // Simpan harga saat pembelian
                ]);

                // Kurangi stok dari ProductSize yang sesuai
                // Tidak perlu query ulang ProductSize::find jika sudah di-eager load dan $item->productSize adalah instance yang benar
                // Namun, untuk memastikan kita bekerja dengan data terbaru sebelum decrement, find() lebih aman jika ada potensi race condition (jarang terjadi di sini)
                $productSizeToUpdate = ProductSize::find($item->product_size_id);
                if ($productSizeToUpdate) {
                    $productSizeToUpdate->decrement('stock', $item->quantity);
                }
            }

            // Hapus item dari keranjang setelah pesanan dibuat
            $user->cartItems()->delete(); // <--- POTENSI MASALAH JIKA COMMIT GAGAL SETELAH INI

            DB::commit(); // <--- JIKA EXCEPTION TERJADI SEBELUM INI, ROLLBACK AKAN MENANGANI

            // Arahkan ke halaman tunggu verifikasi
            return redirect()->route('checkout.waiting_verification', $order)->with('success', 'Bukti pembayaran Anda telah diunggah. Mohon tunggu verifikasi dari admin.');


        } catch (\Exception $e) {
            DB::rollBack();
            // Log error: \Log::error($e->getMessage()); // <--- SANGAT PENTING UNTUK DIAGNOSIS
            \Log::error('Checkout process failed: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('cart.index')->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    public function success(Order $order)
    {
         // Pastikan pesanan milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya tampilkan halaman sukses jika pembayaran sudah diverifikasi (paid)
        if ($order->payment_status !== 'paid') {
            // Jika belum paid, arahkan ke halaman tunggu atau halaman order detail
            return redirect()->route('orders.show', $order)->with('info', 'Pembayaran Anda belum diverifikasi atau gagal.');
        }
        $order->load(['items.productSize.product', 'items.product', 'user']); // Muat relasi untuk view
        return view('checkout.success', compact('order'));
    }

    public function waitingVerification(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        // Jika sudah diverifikasi, langsung ke success page atau order detail
        if ($order->payment_status === 'paid') return redirect()->route('checkout.success', $order);
        if ($order->payment_status === 'failed') return redirect()->route('orders.show', $order)->with('error', 'Verifikasi pembayaran Anda gagal.');

        return view('checkout.waiting_verification', compact('order'));
    }
}