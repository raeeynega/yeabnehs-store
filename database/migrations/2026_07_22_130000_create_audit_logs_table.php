<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Chain integrity fields (hash blockchain)
            $table->unsignedBigInteger('chain_index')->unique();
            $table->timestamp('timestamp');
            $table->string('previous_hash', 64);  // SHA-256 of previous entry
            $table->string('chain_hash', 64);      // SHA-256 of this entry

            // Event data
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 100);
            $table->string('entity_type', 100)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Request context
            $table->string('ip_address', 45);      // IPv4 or IPv6
            $table->string('user_agent', 500)->nullable();
            $table->string('request_url', 2000)->nullable();
            $table->string('request_method', 10)->nullable();

            // Classification
            $table->string('risk_level', 20)->default('low');

            $table->timestamps();

            // Indexes for audit queries (ISO 27001 A.12.4.1)
            $table->index('action');
            $table->index('entity_type');
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('risk_level');
            $table->index('created_at');
            $table->index('chain_index');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
