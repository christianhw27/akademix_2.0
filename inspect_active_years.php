<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AcademicYear;

$activeYears = AcademicYear::where('is_active', 1)->get();
echo "Active Years count: " . $activeYears->count() . "\n";
foreach ($activeYears as $ay) {
    echo "  - ID: " . $ay->id . " | Label: " . $ay->year_label . " | Semester: " . $ay->semester . "\n";
}
