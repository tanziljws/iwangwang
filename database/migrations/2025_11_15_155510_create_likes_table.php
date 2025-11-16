<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('foto_id')->constrained('foto')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id','foto_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
