<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSize; // Import ProductSize
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file gambar
use Illuminate\Validation\Rule; // Untuk validasi Rule::in()

class ProductController extends Controller
{
    /**
     * Daftar ukuran yang valid.
     */
    protected $allRegisteredSizes = ProductSize::AVAILABLE_SIZES; // Menggunakan konstanta dari model
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('sizes')->latest()->paginate(10); // Eager load 'sizes'
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create', ['allRegisteredSizes' => $this->allRegisteredSizes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        $variantsData = $request->validate([
            'variants' => 'nullable|array',
            'variants.*.size' => ['required_with:variants.*.stock', 'distinct', Rule::in($this->allRegisteredSizes)],
            'variants.*.stock' => 'required_with:variants.*.size|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public'); // Simpan di storage/app/public/products
            $productData['image_path'] = $path;
        }

        $product = Product::create($productData);

        if (isset($variantsData['variants'])) {
            foreach ($variantsData['variants'] as $variant) {
                // Pastikan size dan stock ada sebelum membuat
                if (isset($variant['size']) && isset($variant['stock'])) {
                    $product->sizes()->create([
                        'size' => $variant['size'],
                        'stock' => $variant['stock'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan beserta varian ukurannya.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Biasanya tidak diperlukan untuk admin, index dan edit sudah cukup
        return view('admin.products.show', ['product' => $product->load('sizes')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', ['product' => $product->load('sizes'), 'allRegisteredSizes' => $this->allRegisteredSizes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $productData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $variantInput = $request->input('variants', []);

        // Validasi untuk varian
        $request->validate([
            'variants' => 'nullable|array',
            'variants.*.size' => [
                'required_with:variants.*.stock',
                Rule::in($this->allRegisteredSizes),
                // Custom validation to check uniqueness of size for this product, excluding self during update
                function ($attribute, $value, $fail) use ($request, $product, $variantInput) {
                    $currentIndex = explode('.', $attribute)[1];
                    $currentVariantId = $variantInput[$currentIndex]['id'] ?? null;

                    // Check for duplicates within the current submission
                    $sizesInSubmission = collect($variantInput)->pluck('size')->all(); // get all sizes
                    $counts = array_count_values(array_filter($sizesInSubmission)); // count occurrences, filter out nulls if any
                    if (($counts[$value] ?? 0) > 1) {
                        $fail("Ukuran '$value' duplikat dalam inputan.");
                        return;
                    }

                    $query = $product->sizes()->where('size', $value);
                    if ($currentVariantId) {
                        $query->where('id', '!=', $currentVariantId);
                    }
                    if ($query->exists()) {
                        $fail("Ukuran '$value' sudah ada untuk produk ini.");
                    }
                }
            ],
            'variants.*.stock' => 'required_with:variants.*.size|integer|min:0',
            'variants.*.id' => ['nullable', Rule::exists('product_sizes', 'id')->where('product_id', $product->id)],
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $path = $request->file('image')->store('products', 'public');
            $productData['image_path'] = $path;
        }

        $product->update($productData);

        $submittedVariantIds = [];
        if (!empty($variantInput)) {
            foreach ($variantInput as $variantItem) {
                if (isset($variantItem['size']) && isset($variantItem['stock'])) { // Hanya proses jika size dan stock ada
                    $variant = $product->sizes()->updateOrCreate(
                        ['id' => $variantItem['id'] ?? null], // Kunci untuk mencari atau membuat
                        ['size' => $variantItem['size'], 'stock' => $variantItem['stock']] // Nilai untuk diupdate atau dibuat
                    );
                    $submittedVariantIds[] = $variant->id;
                }
            }
        }
        // Hapus varian yang tidak ada dalam inputan (yang dihapus dari form)
        $product->sizes()->whereNotIn('id', $submittedVariantIds)->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui beserta varian ukurannya.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus gambar terkait jika ada (cascade delete pada product_sizes akan menangani varian)
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}