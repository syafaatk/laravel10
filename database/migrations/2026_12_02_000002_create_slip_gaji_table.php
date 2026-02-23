<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slip_gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gaji_id')->constrained('gaji')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Income components
            $table->decimal('gaji_pokok', 15, 0)->default(0);
            $table->decimal('tunjangan_jabatan', 15, 0)->default(0);
            $table->decimal('tunjangan_golongan', 15, 0)->default(0);
            $table->decimal('tunjangan_makan', 15, 0)->default(0);
            $table->decimal('tunjangan_rumah', 15, 0)->default(0);
            $table->decimal('tunjangan_transport', 15, 0)->default(0);
            $table->decimal('tunjangan_tambahan', 15, 0)->default(0);
            $table->decimal('tunjangan_extra', 15, 0)->default(0);
            $table->decimal('premi_jkk_jkm', 15, 0)->default(0);

            // Deduction components
            $table->decimal('potongan_pph21', 15, 0)->default(0);
            $table->decimal('potongan_jmo', 15, 0)->default(0);
            $table->decimal('potongan_lain', 15, 0)->default(0);

            // Calculated totals
            $table->decimal('total_tunjangan', 15, 0)->default(0);
            $table->decimal('penghasilan_bruto', 15, 0)->default(0);
            $table->decimal('total_potongan', 15, 0)->default(0);
            $table->decimal('penghasilan_netto', 15, 0)->default(0);

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['gaji_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slip_gajis');
    }
};