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
        Schema::table('detail_kontrak_users', function (Blueprint $table) {
            // Rename existing columns for clarity to match payslip terms

            // Add new allowance columns
            $table->decimal('tunjangan_golongan', 15, 0)->default(0)->after('gaji_tunjangan_tetap');
            $table->decimal('tunjangan_jabatan', 15, 0)->default(0)->after('tunjangan_golongan');
            $table->decimal('tunjangan_rumah', 15, 0)->default(0)->after('gaji_tunjangan_makan');
            $table->decimal('tunjangan_tambahan', 15, 0)->default(0)->after('tunjangan_rumah');
            $table->decimal('tunjangan_extra', 15, 0)->default(0)->after('tunjangan_tambahan');
            
            // Add new deduction and premium columns
            $table->decimal('premi_jkk_jkm', 15, 0)->default(0)->after('gaji_bpjs')->comment('Premi JKK & JKM ditanggung perusahaan');
            $table->decimal('potongan_pph21', 15, 0)->default(0)->after('premi_jkk_jkm');
            $table->decimal('potongan_jmo', 15, 0)->default(0)->after('potongan_pph21')->comment('Potongan Jaminan Hari Tua (JHT) dari JMO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_kontrak_users', function (Blueprint $table) {
            $table->dropColumn(['tunjangan_golongan','tunjangan_jabatan', 'tunjangan_tambahan', 'tunjangan_extra', 'premi_jkk_jkm', 'potongan_pph21', 'potongan_jmo']);

        });
    }
};