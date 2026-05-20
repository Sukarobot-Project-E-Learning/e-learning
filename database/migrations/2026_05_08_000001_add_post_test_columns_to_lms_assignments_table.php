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
        Schema::table('lms_assignments', function (Blueprint $table) {
            $table->enum('type', ['standard', 'post-test'])
                ->default('standard')
                ->after('program_id');
            $table->unsignedInteger('passing_score')
                ->default(70)
                ->after('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_assignments', function (Blueprint $table) {
            $table->dropColumn(['type', 'passing_score']);
        });
    }
};
