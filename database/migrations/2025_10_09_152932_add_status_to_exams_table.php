<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Modify existing status column to include all lifecycle stages
            DB::statement("ALTER TABLE exams MODIFY status ENUM('draft', 'ready', 'published', 'ongoing', 'closed', 'graded', 'archived') NOT NULL DEFAULT 'draft'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Revert to original status values (if you had them before)
            DB::statement("ALTER TABLE exams MODIFY status ENUM('draft', 'active', 'archived', 'published') NOT NULL DEFAULT 'draft'");
        });
    }
};
