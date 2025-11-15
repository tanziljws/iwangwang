<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kategori');
    }
};
