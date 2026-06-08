<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = ['users', 'students', 'guardians', 'classrooms', 'classroom_students'];

foreach ($tables as $table) {
    if (!Schema::hasTable($table)) {
        echo "Table '$table' does not exist.\n";
        continue;
    }
    echo "Table: $table\n";
    $columns = Schema::getColumnListing($table);
    foreach ($columns as $column) {
        $type = Schema::getColumnType($table, $column);
        echo "  - $column ($type)\n";
    }
    echo "\n";
}

// Let's query a few records from students and users
echo "--- Sample from students table ---\n";
$students = DB::table('students')->limit(3)->get();
print_r($students);

echo "--- Sample from users table ---\n";
$users = DB::table('users')->where('role', 'student')->limit(5)->get();
print_r($users);
