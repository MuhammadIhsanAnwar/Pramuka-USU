<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('birth_place')->nullable()->after('jenis_user');
            $table->string('gender', 20)->nullable()->after('birth_place');
            $table->string('religion', 50)->nullable()->after('gender');
            $table->string('blood_type', 5)->nullable()->after('religion');
            $table->string('hobby')->nullable()->after('blood_type');
            $table->integer('siblings_count')->nullable()->after('hobby');
            $table->string('whatsapp_number', 30)->nullable()->after('phone');
            $table->string('marital_status', 50)->nullable()->after('whatsapp_number');
            $table->string('job')->nullable()->after('marital_status');

            $table->string('domisili_country')->nullable()->after('address');
            $table->string('domisili_province')->nullable()->after('domisili_country');
            $table->string('domisili_city')->nullable()->after('domisili_province');
            $table->string('domisili_district')->nullable()->after('domisili_city');
            $table->string('domisili_village')->nullable()->after('domisili_district');
            $table->string('domisili_rt', 10)->nullable()->after('domisili_village');
            $table->string('domisili_rw', 10)->nullable()->after('domisili_rt');
            $table->string('domisili_postal_code', 10)->nullable()->after('domisili_rw');
            $table->string('domisili_street')->nullable()->after('domisili_postal_code');

            $table->string('asal_country')->nullable()->after('domisili_street');
            $table->string('asal_province')->nullable()->after('asal_country');
            $table->string('asal_city')->nullable()->after('asal_province');
            $table->string('asal_district')->nullable()->after('asal_city');
            $table->string('asal_village')->nullable()->after('asal_district');
            $table->string('asal_rt', 10)->nullable()->after('asal_village');
            $table->string('asal_rw', 10)->nullable()->after('asal_rt');
            $table->string('asal_postal_code', 10)->nullable()->after('asal_rw');
            $table->string('asal_street')->nullable()->after('asal_postal_code');

            $table->string('education_status', 50)->nullable()->after('asal_street');
            $table->string('nim', 100)->nullable()->after('education_status');
            $table->string('kampus')->nullable()->after('nim');
            $table->string('fakultas')->nullable()->after('kampus');
            $table->string('program_studi')->nullable()->after('fakultas');

            $table->string('father_name')->nullable()->after('program_studi');
            $table->string('father_status', 50)->nullable()->after('father_name');
            $table->text('father_address')->nullable()->after('father_status');
            $table->string('father_phone', 30)->nullable()->after('father_address');

            $table->string('mother_name')->nullable()->after('father_phone');
            $table->string('mother_status', 50)->nullable()->after('mother_name');
            $table->text('mother_address')->nullable()->after('mother_status');
            $table->string('mother_phone', 30)->nullable()->after('mother_address');

            $table->string('guardian_name')->nullable()->after('mother_phone');
            $table->string('guardian_status', 50)->nullable()->after('guardian_name');
            $table->text('guardian_address')->nullable()->after('guardian_status');
            $table->string('guardian_phone', 30)->nullable()->after('guardian_address');

            $table->string('satuan')->nullable()->after('guardian_phone');
            $table->string('jabatan')->nullable()->after('satuan');
            $table->string('nta', 50)->nullable()->after('jabatan');
            $table->integer('tahun_masuk_pramuka_usu')->nullable()->after('nta');
            $table->string('nama_omantaru')->nullable()->after('tahun_masuk_pramuka_usu');
            $table->string('golongan', 50)->nullable()->after('nama_omantaru');
            $table->string('tingkatan', 100)->nullable()->after('golongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_place',
                'gender',
                'religion',
                'blood_type',
                'hobby',
                'siblings_count',
                'whatsapp_number',
                'marital_status',
                'job',
                'domisili_country',
                'domisili_province',
                'domisili_city',
                'domisili_district',
                'domisili_village',
                'domisili_rt',
                'domisili_rw',
                'domisili_postal_code',
                'domisili_street',
                'asal_country',
                'asal_province',
                'asal_city',
                'asal_district',
                'asal_village',
                'asal_rt',
                'asal_rw',
                'asal_postal_code',
                'asal_street',
                'education_status',
                'nim',
                'kampus',
                'fakultas',
                'program_studi',
                'father_name',
                'father_status',
                'father_address',
                'father_phone',
                'mother_name',
                'mother_status',
                'mother_address',
                'mother_phone',
                'guardian_name',
                'guardian_status',
                'guardian_address',
                'guardian_phone',
                'satuan',
                'jabatan',
                'nta',
                'tahun_masuk_pramuka_usu',
                'nama_omantaru',
                'golongan',
                'tingkatan',
            ]);
        });
    }
};
