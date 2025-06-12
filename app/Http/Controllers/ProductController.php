<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('sizes'); // Eager load relasi 'sizes'

        // Pencarian berdasarkan nama produk
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        // Filter berdasarkan harga
        if ($request->filled('sort_price')) {
            $sortOrder = $request->input('sort_price');
            if ($sortOrder == 'lowest') {
                $query->orderBy('price', 'asc');
            } elseif ($sortOrder == 'highest') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->latest(); // Urutan default jika tidak ada filter harga
        }

        $products = $query->paginate(10)->appends($request->query()); // Ambil produk, paginasi, dan pertahankan query string

        return view('products.index', compact('products'));
    }

    public function show(Product $product) // Route Model Binding
    {
        $product->load('sizes'); // Eager load relasi 'sizes' untuk produk spesifik ini
        // Jika Anda ingin menampilkan detail produk individual
        // $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}