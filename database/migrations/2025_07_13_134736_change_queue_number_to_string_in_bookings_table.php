<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type; //
use Doctrine\DBAL\Schema\Comparator; 

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            
            if (!Type::hasType('string')) {
                Type::addType('string', 'Doctrine\DBAL\Types\StringType');
            }

            
            $table->string('queue_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            
            
            $table->integer('queue_number')->nullable()->change();
        });
    }
};