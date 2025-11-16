<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('comments') && Schema::hasColumn('comments', 'content') && Schema::hasColumn('comments', 'body')) {
            try {
                DB::table('comments')->whereNull('content')->update([
                    'content' => DB::raw('body')
                ]);
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    public function down(): void
    {
        // no-op
    }
};
