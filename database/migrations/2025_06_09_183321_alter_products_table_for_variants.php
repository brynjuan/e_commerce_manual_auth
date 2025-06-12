<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Pastikan data sudah dimigrasikan atau di-backup jika ini proyek yang sudah berjalan
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
            if (Schema::hasColumn('products', 'size')) {
                $table->dropColumn('size');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kembali jika perlu untuk rollback
            $table->integer('stock')->default(0)->after('price'); // Sesuaikan posisi
            $table->string('size')->nullable()->after('stock'); // Sesuaikan posisi
        });
    }
};
