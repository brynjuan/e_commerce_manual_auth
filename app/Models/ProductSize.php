<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'stock',
    ];

    /**
     * Daftar ukuran yang valid secara global.
     * Anda bisa memindahkan $availableSizes dari ProductController ke sini atau ke config.
     */
    public const AVAILABLE_SIZES = ['S', 'M', 'L', 'XL', 'XXL', 'All Size']; // Tambahkan 'All Size' atau ukuran standar lainnya

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
