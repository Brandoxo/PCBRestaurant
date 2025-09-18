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
        Schema::table('cash_audit', function (Blueprint $table) {
            $table->decimal('initial_amount', 10, 2)->default(3500.00)->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_audit', function (Blueprint $table) {
            $table->dropColumn('initial_amount');
        });
    }
};
