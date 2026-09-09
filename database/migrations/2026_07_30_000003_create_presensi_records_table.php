<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_records', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('presensi_session_id')->constrained('presensi_sessions')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('scanned_at')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'alpha', 'tidak'])->default('hadir');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('method')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->unsignedInteger('distance')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['presensi_session_id', 'user_id']);
            $table->index(['presensi_session_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_records');
    }
};