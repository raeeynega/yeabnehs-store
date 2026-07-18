<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type'); // failed_login, brute_force, suspicious_activity, blocked_request, account_takeover, privilege_escalation, data_exfiltration
            $table->string('severity');   // low, medium, high, critical
            $table->text('description');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('resolved')->default(false);
            $table->string('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'created_at']);
            $table->index(['severity', 'resolved']);
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_events');
    }
};
