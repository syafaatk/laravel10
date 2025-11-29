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
        Schema::create('detail_kontrak_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kontrak')->nullable();
            $table->date('tgl_mulai_kontrak')->nullable();
            $table->date('tgl_selesai_kontrak')->nullable();
            $table->decimal('gaji_pokok', 15, 0)->default(0);
            $table->decimal('gaji_tunjangan_tetap', 15, 0)->default(0);
            $table->decimal('gaji_tunjangan_makan', 15, 0)->default(0);
            $table->decimal('gaji_tunjangan_transport', 15, 0)->default(0);
            $table->decimal('gaji_tunjangan_lain', 15, 0)->default(0);
            $table->decimal('gaji_bpjs', 15, 0)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index untuk query yang sering
            $table->index('user_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kontrak_users');
    }
};