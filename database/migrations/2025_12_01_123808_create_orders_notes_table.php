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
        if(!Schema::hasTable('orders_notes')) {
        Schema::create('orders_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->text('content')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
