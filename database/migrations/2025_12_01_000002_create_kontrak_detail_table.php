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
        Schema::create('kontrak_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->constrained('kontraks')->onDelete('cascade');
            $table->string('title_spph')->nullable();
            $table->date('tgl_mulai_spph')->nullable();
            $table->date('tgl_selesai_spph')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_details');
    }
};