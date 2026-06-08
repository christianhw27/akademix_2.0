<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Classroom;
use App\Models\AcademicYear;

$activeYear = AcademicYear::where('is_active', 1)->first();
$classrooms = Classroom::where('academic_year_id', $activeYear->id)->get();

foreach ($classrooms as $c) {
    echo "Classroom ID: " . $c->id . " | Class: " . ($c->studyClass->class_name ?? 'N/A') . "\n";
    $student = $c->students()->first();
    if ($student) {
        echo "  - Student: " . $student->user->full_name . " | Username: " . $student->user->username . "\n";
    } else {
        echo "  - No student in this class!\n";
    }
}
