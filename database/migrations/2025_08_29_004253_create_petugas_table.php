<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_petugas');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('no_hp', 15)->nullable();
            $table->string('jabatan', 50);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
