<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->boolean('status')->default(1);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('galeri');
    }
};
