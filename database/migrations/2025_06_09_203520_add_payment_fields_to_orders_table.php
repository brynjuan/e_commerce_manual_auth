<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_payment_fields_to_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('shipping_address');
            $table->string('payment_proof_path')->nullable()->after('payment_method');
            $table->string('payment_status')->default('unpaid')->after('payment_proof_path'); // unpaid, waiting_verification, paid, failed
            $table->foreignId('verified_by_admin_id')->nullable()->after('payment_status')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['verified_by_admin_id']);
            $table->dropColumn(['payment_method', 'payment_proof_path', 'payment_status', 'verified_by_admin_id']);
        });
    }
};
