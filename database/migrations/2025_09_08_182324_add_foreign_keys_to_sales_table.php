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
        Schema::table('sales', function (Blueprint $table) {
            $table->foreign(['order_id'], 'sales_ibfk_1')->references(['id'])->on('orders')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['product_id'], 'sales_ibfk_2')->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'sales_ibfk_3')->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['cash_audit_id'], 'sales_ibfk_4')->references(['id'])->on('cash_audit')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_ibfk_1');
            $table->dropForeign('sales_ibfk_2');
            $table->dropForeign('sales_ibfk_3');
            $table->dropForeign('sales_ibfk_4');
        });
    }
};
