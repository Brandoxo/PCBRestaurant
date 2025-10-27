<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create cash_floats table with default data fields
        Schema::create('cash_floats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('type', ['open', 'adjust', 'close'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_floats');
    }
};
