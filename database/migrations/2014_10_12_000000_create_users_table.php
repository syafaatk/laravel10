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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('motor')->nullable();
            $table->string('ukuran_baju')->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->string('attachment_ttd')->nullable();
            $table->integer('nopeg')->nullable()->unique();
            $table->string('kontrak')->nullable();
            $table->string('jabatan')->nullable(); // karyawan, admin, manager, hrd
            $table->string('norek')->nullable();
            $table->string('bank')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
