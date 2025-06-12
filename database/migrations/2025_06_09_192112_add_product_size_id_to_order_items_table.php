<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // onDelete('set null') agar jika product size dihapus, order item tetap ada
            // onDelete('cascade') akan menghapus order item jika product size dihapus
            $table->foreignId('product_size_id')->nullable()->after('product_id')->constrained('product_sizes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') { // Penanganan untuk SQLite
                $table->dropForeign(['product_size_id']);
            }
            $table->dropColumn('product_size_id');
        });
    }
};