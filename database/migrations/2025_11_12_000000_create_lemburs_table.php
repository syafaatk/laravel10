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
        Schema::create('lemburs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis', ['weekday', 'weekend', 'holiday'])->default('weekday');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi_jam');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('approver')->nullable();
            $table->decimal('estimasi_uang_lembur', 10, 2)->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lemburs');
    }
};