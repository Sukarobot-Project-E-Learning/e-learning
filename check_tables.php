<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
echo "Tables before:\n";
foreach($tables as $t) {
    $tarr = (array)$t;
    $tname = array_values($tarr)[0];
    if (strpos($tname, 'course_') !== false) {
        echo $tname . "\n";
    }
}
Illuminate\Support\Facades\Schema::dropIfExists('course_submissions');
Illuminate\Support\Facades\Schema::dropIfExists('course_assignments');
Illuminate\Support\Facades\Schema::dropIfExists('course_progresses');
Illuminate\Support\Facades\Schema::dropIfExists('course_lessons');
Illuminate\Support\Facades\Schema::dropIfExists('course_sections');
Illuminate\Support\Facades\DB::table('migrations')->where('migration', 'like', '%create_course%')->delete();
echo "Tables dropped.\n";
