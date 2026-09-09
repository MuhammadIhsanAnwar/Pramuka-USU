<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendances')) {
            DB::statement("ALTER TABLE `attendances` MODIFY `scanned_at` TIMESTAMP NULL DEFAULT NULL");
            DB::statement("ALTER TABLE `attendances` MODIFY `status` ENUM('hadir', 'izin', 'alpha', 'terlambat', 'tidak') NOT NULL DEFAULT 'hadir'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            DB::statement("ALTER TABLE `attendances` MODIFY `scanned_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
            DB::statement("ALTER TABLE `attendances` MODIFY `status` ENUM('hadir', 'izin', 'alpha', 'terlambat') NOT NULL DEFAULT 'hadir'");
        }
    }
};
