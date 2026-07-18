<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->string('trainable_type');
            $table->unsignedBigInteger('trainable_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, completed, cancelled
            $table->decimal('total', 8, 2);
            $table->timestamps();

            $table->index(['trainable_type', 'trainable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
