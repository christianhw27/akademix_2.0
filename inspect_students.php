<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\AttendanceRecord;
use App\Models\Material;
use App\Models\Assignment;

$activeYear = AcademicYear::where('is_active', 1)->first();
echo "Active Year: " . ($activeYear ? $activeYear->year_label . " (" . $activeYear->semester . ")" : "None") . "\n";

// Let's get a few random students and check their data
$students = Student::with('user')->take(5)->get();
foreach ($students as $student) {
    echo "=====================================\n";
    echo "Student ID: " . $student->id . " | Name: " . $student->user->full_name . " | Username: " . $student->user->username . "\n";
    
    $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->first();
    if (!$classroom) {
        echo "  NO ACTIVE CLASSROOM!\n";
        continue;
    }
    echo "  Classroom ID: " . $classroom->id . " | Grade Level: " . $classroom->grade_level . " | Class: " . ($classroom->studyClass->name ?? 'N/A') . "\n";
    
    $schedules = Schedule::where('classroom_id', $classroom->id)->get();
    echo "  Schedules Count: " . $schedules->count() . "\n";
    
    $materials = Material::where('classroom_id', $classroom->id)->get();
    echo "  Materials Count: " . $materials->count() . "\n";
    
    $assignments = Assignment::where('classroom_id', $classroom->id)->get();
    echo "  Assignments Count: " . $assignments->count() . "\n";
    
    $attendance = AttendanceRecord::where('student_id', $student->id)->get();
    echo "  Attendance Records Count: " . $attendance->count() . "\n";
}
