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
            $table->integer('gaji_tunjangan_tetap')->nullable()->after('bank');
            $table->integer('gaji_tunjangan_makan')->after('gaji_tunjangan_tetap')->default('1000000');
            $table->integer('gaji_tunjangan_transport')->nullable()->after('gaji_tunjangan_makan')->default('2000000');
            $table->integer('gaji_pokok')->nullable()->after('gaji_tunjangan_transport');
            $table->integer('gaji_bpjs')->nullable()->after('gaji_pokok')->default('150000');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gaji_tunjangan_tetap');
            $table->dropColumn('gaji_tunjangan_makan');
            $table->dropColumn('gaji_tunjangan_transport');
            $table->dropColumn('gaji_pokok');
            $table->dropColumn('gaji_bpjs');
        });
    }
};