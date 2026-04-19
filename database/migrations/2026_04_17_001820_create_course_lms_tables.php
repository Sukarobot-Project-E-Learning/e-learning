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
        Schema::dropIfExists('lms_submissions');
        Schema::dropIfExists('lms_assignments');
        Schema::dropIfExists('lms_progresses');
        Schema::dropIfExists('lms_lessons');
        Schema::dropIfExists('lms_sections');

        Schema::create('lms_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('program_id');
            $table->string('title');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('lms_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('lms_sections')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['text', 'video']);
            $table->longText('content')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('lms_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('lms_lessons')->onDelete('cascade');
            $table->boolean('is_completed')->default(true);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('lms_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('program_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('allowed_extensions')->default('pdf,docx,zip');
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('lms_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('lms_assignments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_submissions');
        Schema::dropIfExists('lms_assignments');
        Schema::dropIfExists('lms_progresses');
        Schema::dropIfExists('lms_lessons');
        Schema::dropIfExists('lms_sections');
    }
};
