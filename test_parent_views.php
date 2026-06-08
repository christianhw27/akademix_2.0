<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ParentController;
use Illuminate\Http\Request;

// Find student Raka's parent
$student = Student::where('nis', '20240001')->first();
if (!$student || !$student->guardian_id) {
    echo "Student Raka or guardian not found!\n";
    exit;
}

$guardianUser = $student->guardian->user;
if (!$guardianUser) {
    echo "Guardian user not found!\n";
    exit;
}

Auth::login($guardianUser);
session(['active_student_id' => $student->id]);

echo "Logged in as Parent: " . Auth::user()->full_name . " monitoring child: " . $student->user->full_name . "\n";

$controller = new ParentController();

try {
    echo "\n--- Testing Parent Dashboard ---\n";
    $response = $controller->dashboard();
    $data = $response->getData();
    echo "Dashboard loaded. Active classroom: " . ($data['classroom'] ? $data['classroom']->studyClass->class_name : 'None') . "\n";
    echo "Schedules count: " . $data['schedules']->count() . "\n";
    print_r($data['stats']);
} catch (\Exception $e) {
    echo "Dashboard Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

try {
    echo "\n--- Testing Parent Assignments ---\n";
    $response = $controller->assignments();
    $data = $response->getData();
    echo "Assignments loaded. Subjects count: " . $data['subjects']->count() . "\n";
    foreach ($data['subjects'] as $subject) {
        echo "  Subject: " . $subject->name . " (" . $subject->code . ")\n";
        echo "    Materials count: " . $subject->materials_count . "\n";
        echo "    Assignments count: " . $subject->assignments_count . "\n";
    }
} catch (\Exception $e) {
    echo "Assignments Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

try {
    echo "\n--- Testing Parent Attendance ---\n";
    $response = $controller->attendance(new Request());
    $data = $response->getData();
    echo "Attendance loaded. Display month: " . $data['displayMonthName'] . "\n";
    echo "Summary: ";
    print_r($data['summary']);
} catch (\Exception $e) {
    echo "Attendance Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
