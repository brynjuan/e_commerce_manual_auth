<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        // 'stock', // Dihapus, akan dikelola oleh ProductSize
        'image_path',
        // 'size',  // Dihapus, akan dikelola oleh ProductSize
    ];

    // Jika Anda ingin relasi ke order_items atau cart_items dari sini
    // public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    // public function cartItems(): HasMany { return $this->hasMany(CartItem::class); }

    /**
     * Mendapatkan URL lengkap untuk gambar produk.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }
        // Jika tidak ada gambar, kembalikan path ke gambar placeholder
        return asset('images/placeholder.png'); // Pastikan Anda memiliki gambar placeholder di public/images/placeholder.png
    }

    /**
     * Relasi ke ukuran dan stok produk.
     */
    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

}