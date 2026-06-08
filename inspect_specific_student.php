<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\AcademicYear;

$activeYear = AcademicYear::where('is_active', 1)->first();
echo "Active Year: " . ($activeYear ? $activeYear->year_label : "None") . "\n";

// Get user siswa.101
$user = User::where('username', 'siswa.101')->first();
if ($user) {
    echo "User found: " . $user->full_name . " (ID: " . $user->id . ")\n";
    $student = $user->student;
    if ($student) {
        echo "Student found: NIS: " . $student->nis . " (ID: " . $student->id . ")\n";
        $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->first();
        if ($classroom) {
            echo "Classroom: ID " . $classroom->id . " | Grade " . $classroom->grade_level . " | Class: " . ($classroom->studyClass->class_name ?? 'N/A') . "\n";
            echo "Schedules count: " . \App\Models\Schedule::where('classroom_id', $classroom->id)->count() . "\n";
            $schedules = \App\Models\Schedule::where('classroom_id', $classroom->id)->get();
            foreach ($schedules as $s) {
                echo "  - " . $s->day_of_week . " | " . $s->start_time . " - " . $s->end_time . "\n";
            }
            echo "Materials count: " . \App\Models\Material::where('classroom_id', $classroom->id)->count() . "\n";
            echo "Assignments count: " . \App\Models\Assignment::where('classroom_id', $classroom->id)->count() . "\n";
            echo "Attendance records count: " . \App\Models\AttendanceRecord::where('student_id', $student->id)->count() . "\n";
        } else {
            echo "No classroom in active year!\n";
        }
    } else {
        echo "No student relation found for this user!\n";
    }
} else {
    echo "User siswa.101 not found!\n";
}
