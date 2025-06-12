<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Tambahkan product_size_id setelah product_id
            // Jadikan nullable untuk sementara jika ada data lama, atau atur default jika memungkinkan
            // onDelete('cascade') akan menghapus cart item jika product size dihapus
            $table->foreignId('product_size_id')->nullable()->after('product_id')->constrained('product_sizes')->onDelete('cascade');

            // Jika Anda ingin membuat product_id nullable karena product_size_id sudah cukup
            // $table->foreignId('product_id')->nullable()->change();
            // Namun, untuk saat ini kita biarkan product_id tetap ada untuk kemudahan akses ke info produk umum.
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_size_id']);
            $table->dropColumn('product_size_id');
        });
    }
};