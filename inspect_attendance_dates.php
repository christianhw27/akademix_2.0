<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AttendanceSession;
use Illuminate\Support\Facades\DB;

$sessions = AttendanceSession::select(
    DB::raw('YEAR(attendance_date) as year'),
    DB::raw('MONTH(attendance_date) as month'),
    DB::raw('count(*) as count')
)
->groupBy('year', 'month')
->orderBy('year')
->orderBy('month')
->get();

echo "Attendance Sessions by Month:\n";
foreach ($sessions as $s) {
    echo "  - Year: " . $s->year . " | Month: " . $s->month . " | Count: " . $s->count . "\n";
}
