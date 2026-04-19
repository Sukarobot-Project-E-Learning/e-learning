<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    Illuminate\Support\Facades\Schema::dropIfExists('course_submissions');
    Illuminate\Support\Facades\Schema::dropIfExists('course_assignments');
    Illuminate\Support\Facades\Schema::dropIfExists('course_progresses');
    Illuminate\Support\Facades\Schema::dropIfExists('course_lessons');
    Illuminate\Support\Facades\Schema::dropIfExists('course_sections');
    // Important: we also need to delete the migration record so it runs fully!
    Illuminate\Support\Facades\DB::table('migrations')->where('migration', 'like', '%create_course_lms_tables%')->delete();
    echo "Dropped tables and removed migration record.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
