<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size'); // Contoh: 'S', 'M', 'L', 'All Size'
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'size']); // Setiap produk hanya boleh memiliki satu entri per ukuran
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_sizes');
    }
};