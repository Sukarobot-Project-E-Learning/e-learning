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
        Schema::table('program_approvals', function (Blueprint $table) {
            $table->longText('lms_curriculum_json')->nullable()->after('benefits');
            $table->longText('lms_assignment_json')->nullable()->after('lms_curriculum_json');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_approvals', function (Blueprint $table) {
            $table->dropColumn(['lms_curriculum_json', 'lms_assignment_json']);
        });
    }
};
