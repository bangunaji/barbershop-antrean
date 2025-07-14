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
        Schema::table('bookings', function (Blueprint $table) {
            
            $table->integer('queue_number')->nullable()->change();
            
            $table->integer('sort_order')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            
            
            $table->integer('queue_number')->nullable(false)->change();
            $table->integer('sort_order')->nullable(false)->change();
        });
    }
};