<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AttendanceSession;
use App\Models\AcademicYear;

$activeYear = AcademicYear::where('is_active', 1)->first();
echo "Active Year: " . ($activeYear ? $activeYear->year_label . " (" . $activeYear->semester . ")" : "None") . "\n";

$classrooms = Classroom::where('academic_year_id', $activeYear->id)->with('studyClass')->get();
echo "Total classrooms in active year: " . $classrooms->count() . "\n\n";

printf("%-5s | %-15s | %-10s | %-9s | %-11s | %-11s | %-10s\n", "ID", "Name", "Schedules", "Materials", "Assignments", "Att Sessions", "Students");
echo str_repeat("-", 85) . "\n";
foreach ($classrooms as $c) {
    $schedules = Schedule::where('classroom_id', $c->id)->count();
    $materials = Material::where('classroom_id', $c->id)->count();
    $assignments = Assignment::where('classroom_id', $c->id)->count();
    $attendance = AttendanceSession::where('classroom_id', $c->id)->count();
    $students = $c->students()->count();
    
    printf("%-5d | %-15s | %-10d | %-9d | %-11d | %-11d | %-10d\n", 
        $c->id, 
        $c->studyClass->class_name ?? 'N/A', 
        $schedules, 
        $materials, 
        $assignments, 
        $attendance,
        $students
    );
}
