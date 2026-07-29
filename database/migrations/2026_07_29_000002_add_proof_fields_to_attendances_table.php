<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->string('photo_path')->nullable()->after('notes');
            $table->string('method')->nullable()->after('photo_path');
            $table->string('device')->nullable()->after('method');
            $table->string('browser')->nullable()->after('device');
            $table->string('ip_address', 45)->nullable()->after('browser');
            $table->unsignedInteger('distance')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropColumn(['distance', 'ip_address', 'browser', 'device', 'method', 'photo_path']);
        });
    }
};
