<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galeri_id')->constrained('galeri')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file');
            $table->string('alt_text')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('foto');
    }
};
