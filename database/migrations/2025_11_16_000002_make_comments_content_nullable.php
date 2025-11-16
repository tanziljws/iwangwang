<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('comments') && Schema::hasColumn('comments', 'content')) {
            // Make `content` nullable to avoid NOT NULL constraint failures
            try {
                DB::statement('ALTER TABLE `comments` MODIFY `content` TEXT NULL');
            } catch (\Throwable $e) {
                // ignore if DB driver doesn't support this exact syntax
            }
        }
    }

    public function down(): void
    {
        // no-op (we won't force NOT NULL back)
    }
};
