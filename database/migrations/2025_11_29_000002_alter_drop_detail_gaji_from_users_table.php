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
            $table->dropColumn([
                'gaji_pokok',
                'gaji_tunjangan_tetap',
                'gaji_tunjangan_makan',
                'gaji_tunjangan_transport',
                'gaji_tunjangan_lain',
                'gaji_bpjs',
                'kontrak',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('gaji_pokok', 15, 0)->default(0)->after('bank');
            $table->decimal('gaji_tunjangan_tetap', 15, 0)->default(0)->after('gaji_pokok');
            $table->decimal('gaji_tunjangan_makan', 15, 0)->default(0)->after('gaji_tunjangan_tetap');
            $table->decimal('gaji_tunjangan_transport', 15, 0)->default(0)->after('gaji_tunjangan_makan');
            $table->decimal('gaji_tunjangan_lain', 15, 0)->default(0)->after('gaji_tunjangan_transport');
            $table->decimal('gaji_bpjs', 15, 0)->default(0)->after('gaji_tunjangan_lain');
            $table->string('kontrak')->nullable()->after('gaji_bpjs');
        });
    }
};