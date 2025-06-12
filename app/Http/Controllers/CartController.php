<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductSize;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource (user's cart).
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()
            ->with(['productSize.product', 'product']) // Eager load productSize dan product terkaitnya
            ->get();
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productSize = ProductSize::with('product')->findOrFail($request->product_size_id);
        $product = $productSize->product; // Akses produk dari relasi ProductSize
        $user = Auth::user();

        // Validasi apakah product_size_id benar milik product_id yang dikirim
        if ($productSize->product_id != $request->product_id) {
            return redirect()->back()->with('error', 'Varian ukuran tidak valid untuk produk ini.');
        }

        // Cek apakah produk dengan ukuran yang sama sudah ada di keranjang
        $cartItem = $user->cartItems()
                         ->where('product_id', $product->id)
                         ->where('product_size_id', $productSize->id)
                         ->first();

        $requestedQuantity = (int)$request->quantity;
        $currentCartQuantity = $cartItem ? $cartItem->quantity : 0;

        if (($currentCartQuantity + $requestedQuantity) > $productSize->stock) {
            return redirect()->back()->with('error', 'Stok produk untuk ukuran ' . $productSize->size . ' tidak mencukupi. Stok tersedia: ' . $productSize->stock);
        }

        if ($cartItem) {
            // Jika sudah ada, update jumlah
            $cartItem->quantity += $requestedQuantity;
            $cartItem->save();
        } else {
            // Jika belum ada, buat item baru
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_size_id' => $productSize->id,
                'quantity' => $requestedQuantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Pastikan item keranjang milik user yang sedang login
        if ($cartItem->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $requestedQuantity = (int)$request->quantity;

        // Cek stok pada ProductSize terkait
        if ($requestedQuantity > $cartItem->productSize->stock) {
            return redirect()->route('cart.index')->with('error', 'Stok produk untuk ukuran ' . $cartItem->productSize->size . ' tidak mencukupi. Stok tersedia: ' . $cartItem->productSize->stock);
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Jumlah produk di keranjang berhasil diperbarui!');
    }

    /**
     * Remove a cart item.
     */
    public function destroy(CartItem $cartItem)
    {
         if ($cartItem->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}