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
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->index('order_id');
            $table->unsignedBigInteger('product_id')->index('product_id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->unsignedBigInteger('cash_audit_id')->nullable()->index('cash_audit_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 10);
            $table->decimal('subtotal', 10);
            $table->timestamp('date_time')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
