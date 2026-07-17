<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('history_pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('history_pages', 'photo_paths')) {
                $table->json('photo_paths')->nullable()->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('history_pages', function (Blueprint $table): void {
            if (Schema::hasColumn('history_pages', 'photo_paths')) {
                $table->dropColumn('photo_paths');
            }
        });
    }
};
