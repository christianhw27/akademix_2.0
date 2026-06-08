<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Query attendance sessions for Classroom ID 5
$sessionsClass5 = DB::table('attendance_sessions')
    ->where('classroom_id', 5)
    ->get();

echo "Attendance sessions for Classroom ID 5:\n";
foreach ($sessionsClass5 as $s) {
    echo "  - Session ID: " . $s->id . " | Date: " . $s->attendance_date . " | Subject ID: " . $s->subject_id . "\n";
}

// Query attendance records for student ID of user ID 1812 (siswa.101)
$student = DB::table('students')->where('user_id', 1812)->first();
if ($student) {
    echo "\nStudent ID: " . $student->id . " | NIS: " . $student->nis . "\n";
    $records = DB::table('attendance_records')
        ->where('student_id', $student->id)
        ->get();
    echo "Total attendance records: " . $records->count() . "\n";
    foreach ($records as $r) {
        echo "  - Record ID: " . $r->id . " | Session ID: " . $r->attendance_session_id . " | Status: " . $r->status . "\n";
    }
}
