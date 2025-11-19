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
        if (!Schema::hasColumn('sales', 'is_room')) {   
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('is_room')->default(false)->after('is_courtesy');
        });
    }}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('is_room');
        });
    }
};
