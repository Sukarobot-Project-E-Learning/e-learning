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
        Schema::table('lms_progresses', function (Blueprint $table) {
            $table->unique(['user_id', 'lesson_id'], 'lms_progresses_user_lesson_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_progresses', function (Blueprint $table) {
            $table->dropUnique('lms_progresses_user_lesson_unique');
        });
    }
};
