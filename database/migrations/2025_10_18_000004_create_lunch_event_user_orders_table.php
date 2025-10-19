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
        Schema::create('lunch_event_user_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lunch_event_id')->constrained('lunch_events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending')->nullable(); // pending, confirmed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('launch_event_user_orders');
    }
};