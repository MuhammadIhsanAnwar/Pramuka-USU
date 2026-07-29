<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_agendas', function (Blueprint $table): void {
            $table->decimal('latitude', 10, 7)->nullable()->after('location');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->unsignedInteger('radius')->default(500)->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('event_agendas', function (Blueprint $table): void {
            $table->dropColumn(['radius', 'longitude', 'latitude']);
        });
    }
};
