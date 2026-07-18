<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('frequency')->nullable()->after('preferred_time');
            $table->integer('sessions_per_week')->default(1)->after('frequency');
            $table->decimal('price_per_session', 8, 2)->nullable()->after('sessions_per_week');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['frequency', 'sessions_per_week', 'price_per_session']);
        });
    }
};
