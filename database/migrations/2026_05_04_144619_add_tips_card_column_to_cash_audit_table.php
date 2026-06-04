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
            $table->decimal('tips_card', 10, 2)->nullable()->after('tips_cash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_audit', function (Blueprint $table) {
            $table->dropColumn('tips_card');
        });
    }
};
