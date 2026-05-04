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
    if (!Schema::hasTable('shifts')) {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name_shift', 50);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
        });
    
        // Insertar turnos por defecto
        DB::table('shifts')->insert([
            [
                'name_shift' => 'Matutino',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'is_active' => true,
            ],
            [
                'name_shift' => 'Vespertino',
                'start_time' => '16:00:00',
                'end_time' => '00:00:00',
                'is_active' => true,
            ]
        ]);
    }
}
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
