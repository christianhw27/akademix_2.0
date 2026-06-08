<?php
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find siswa.101
$user = User::where('username', 'siswa.101')->first();
if (!$user) {
    echo "User siswa.101 not found!\n";
    exit;
}

Auth::login($user);
echo "Logged in as: " . Auth::user()->full_name . " (Role: " . Auth::user()->role . ")\n";

$controller = new StudentController();

try {
    echo "\n--- Testing Dashboard ---\n";
    $response = $controller->dashboard();
    $data = $response->getData();
    echo "Dashboard loaded. Active classroom: " . ($data['classroom'] ? $data['classroom']->studyClass->class_name : 'None') . "\n";
    echo "Today's schedules count: " . $data['todaySchedules']->count() . "\n";
    print_r($data['stats']);
} catch (\Exception $e) {
    echo "Dashboard Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

try {
    echo "\n--- Testing Materials (Jadwal page) ---\n";
    $response = $controller->materials(new Request());
    $data = $response->getData();
    echo "Materials loaded. Classroom: " . ($data['classroom'] ? $data['classroom']->studyClass->class_name : 'None') . "\n";
    echo "Schedules grouped by day keys: " . implode(', ', array_keys($data['schedulesByDay']->toArray())) . "\n";
    foreach ($data['schedulesByDay'] as $day => $schedules) {
        echo "  $day: " . $schedules->count() . " schedules\n";
        foreach ($schedules as $s) {
            echo "    - " . $s->start_time . " - " . $s->end_time . ": " . ($s->subject->subject_name ?? $s->subject->name ?? 'N/A') . " by " . $s->teacher->user->full_name . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Materials Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

try {
    echo "\n--- Testing Assignments (Kelas page) ---\n";
    $response = $controller->assignments();
    $data = $response->getData();
    echo "Assignments loaded. Subjects count: " . $data['subjects']->count() . "\n";
    foreach ($data['subjects'] as $subject) {
        echo "  Subject: " . ($subject->subject_name ?? $subject->name) . " (" . $subject->code . ")\n";
        echo "    Materials count: " . $subject->materials_count . "\n";
        echo "    Assignments count: " . $subject->assignments_count . "\n";
    }
} catch (\Exception $e) {
    echo "Assignments Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

try {
    echo "\n--- Testing Attendance ---\n";
    $response = $controller->attendance(new Request());
    $data = $response->getData();
    echo "Attendance loaded. Display month: " . $data['displayMonthName'] . "\n";
    echo "Summary: ";
    print_r($data['summary']);
    echo "Daily statuses (non-null counts): " . count(array_filter($data['dailyStatuses'])) . "\n";
} catch (\Exception $e) {
    echo "Attendance Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
