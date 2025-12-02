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
        Schema::create('kontrak_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_detail_id')->constrained('kontrak_details')->onDelete('cascade');
            $table->string('bulan_tahun')->nullable();
            $table->date('tgl_periode_mulai')->nullable();
            $table->date('tgl_periode_selesai')->nullable();
            $table->boolean('is_lock')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_laporans');
    }
};