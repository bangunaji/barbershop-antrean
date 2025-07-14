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
        Schema::create('special_operating_hours', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); 
            $table->time('open_time')->nullable(); 
            $table->time('close_time')->nullable(); 
            $table->boolean('is_closed')->default(false); 
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_operating_hours');
    }
};