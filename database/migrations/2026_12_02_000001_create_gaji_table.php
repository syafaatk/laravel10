<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gajis', function (Blueprint $table) {
            $table->id();
            $table->string('periode_bulan')->comment('e.g., Januari 2026');
            $table->date('rentang_mulai');
            $table->date('rentang_selesai');
            $table->enum('status', ['draft', 'terkunci', 'dibayar'])->default('draft');
            $table->timestamps();

            $table->unique(['rentang_mulai', 'rentang_selesai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};