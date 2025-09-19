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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name'); 
            $table->string('customer_phone')->nullable();
            $table->string('booking_type'); 
            $table->date('booking_date');
            $table->time('booking_time')->nullable(); 
            
            $table->integer('queue_number')->nullable(); // Diubah jadi nullable
            $table->integer('sort_order')->default(0); 
            $table->string('arrival_status')->default('waiting'); 
            $table->string('booking_status')->default('active'); 

            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending'); // Tambahan

            $table->unsignedInteger('total_price')->default(0);
            $table->unsignedInteger('total_duration_minutes')->default(0)->nullable(); 
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['booking_date', 'queue_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};