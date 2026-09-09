<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_sessions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_agenda_id')->constrained('event_agendas')->restrictOnDelete();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('radius')->default(500);
            $table->uuid('qr_token')->nullable()->unique();
            $table->string('qr_code_path')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['event_agenda_id', 'starts_at']);
            $table->index(['status', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_sessions');
    }
};