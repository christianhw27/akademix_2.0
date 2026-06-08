<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$studentUsers = User::where('role', 'student')->get();
$missingStudentProfile = 0;

foreach ($studentUsers as $user) {
    if (!$user->student) {
        $missingStudentProfile++;
        echo "User ID: " . $user->id . " | Name: " . $user->full_name . " has NO student profile!\n";
    }
}

echo "Total student users: " . $studentUsers->count() . "\n";
echo "Users with missing student profile: $missingStudentProfile\n";
