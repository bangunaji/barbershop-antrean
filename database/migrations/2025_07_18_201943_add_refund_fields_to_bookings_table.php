<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->enum('refund_status', ['none', 'requested', 'approved', 'rejected'])->default('none');
        $table->text('refund_reason')->nullable();
        $table->timestamp('refunded_at')->nullable();
    });
}

};
