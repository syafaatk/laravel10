<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->date('review_date');
            $table->date('period_start');
            $table->date('period_end');
            $table->json('scores'); // e.g., {"communication": 5, "teamwork": 4, ...}
            $table->decimal('overall_score', 5, 2);
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_pegawais');
    }
};
