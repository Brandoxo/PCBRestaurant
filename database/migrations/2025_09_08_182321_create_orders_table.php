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
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('mesa_id')->nullable()->index('mesa_id');
            $table->dateTime('date_time')->nullable()->useCurrent();
            $table->enum('status', ['En curso', 'Completada', 'Cancelada'])->nullable()->default('En curso');
            $table->decimal('total', 10)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
