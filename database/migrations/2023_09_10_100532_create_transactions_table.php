<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->nullable()->default(null)->index();
            $table->bigInteger('customer_id')->nullable()->default(null)->index();
            $table->decimal('balance', 16, 2)->nullable()->default(0.0);
            $table->decimal('purchase_price', 16, 2)->nullable()->default(0.0);
            $table->decimal('selling_price', 16, 2)->nullable()->default(0.0);
            $table->decimal('profit', 16, 2)->nullable()->default(0.0);
            $table->boolean('status')->nullable()->default(false)->comment('1 = Sudah Bayar, 0 = Belum Bayar, 2 = Tambah Saldo, 3 = Kurang Saldo');
            $table->text('description')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
