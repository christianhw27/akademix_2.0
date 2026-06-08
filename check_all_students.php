<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\AcademicYear;

$activeYear = AcademicYear::where('is_active', 1)->first();
if (!$activeYear) {
    echo "No active academic year found!\n";
    exit;
}

$students = Student::all();
$studentsWithClassroom = 0;
$studentsWithoutClassroom = 0;

$sampleWithout = [];

foreach ($students as $student) {
    $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->first();
    if ($classroom) {
        $studentsWithClassroom++;
    } else {
        $studentsWithoutClassroom++;
        if (count($sampleWithout) < 5) {
            $sampleWithout[] = [
                'id' => $student->id,
                'username' => $student->user->username ?? 'N/A',
                'name' => $student->user->full_name ?? 'N/A',
                'nis' => $student->nis
            ];
        }
    }
}

echo "Total students: " . $students->count() . "\n";
echo "Students WITH active classroom: $studentsWithClassroom\n";
echo "Students WITHOUT active classroom: $studentsWithoutClassroom\n";
echo "\nSample students without active classroom:\n";
print_r($sampleWithout);
