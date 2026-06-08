<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$nullUsernames = User::whereNull('username')->orWhere('username', '')->get();
echo "Users with null/empty username: " . $nullUsernames->count() . "\n";
foreach ($nullUsernames as $u) {
    echo "  - ID: " . $u->id . " | Role: " . $u->role . " | Name: " . $u->full_name . " | Username: '" . $u->username . "'\n";
}

$activeYear = \App\Models\AcademicYear::where('is_active', 1)->first();
echo "Active Year: " . ($activeYear ? $activeYear->year_label . " (" . $activeYear->semester . ")" : "None") . "\n";
