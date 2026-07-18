<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('method'); // cbe, telebirr
            $table->string('status')->default('pending'); // pending, verified, failed
            $table->decimal('amount', 10, 2);
            $table->string('account_number')->nullable(); // sender account or phone
            $table->string('transaction_ref')->nullable(); // CBE reference or Telebirr tx ID
            $table->string('sender_name')->nullable();
            $table->string('sender_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
