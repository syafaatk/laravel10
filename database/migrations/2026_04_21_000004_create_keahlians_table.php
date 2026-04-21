<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keahlians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_keahlian');
            $table->enum('tingkat', ['Dasar', 'Menengah', 'Mahir', 'Ahli']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keahlians');
    }
};