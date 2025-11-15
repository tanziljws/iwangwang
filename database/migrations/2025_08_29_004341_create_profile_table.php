<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('profile', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('Tentang Sekolah');
            $table->longText('isi')->nullable();
            $table->timestamps();
        });
        
    }

    public function down(): void {
        Schema::dropIfExists('profile');
    }
};

