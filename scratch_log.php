<?php
if (!file_exists('storage/logs/laravel.log')) {
    echo "No log file found.\n";
    exit;
}
$log = file_get_contents('storage/logs/laravel.log');
$lines = explode("\n", $log);
$exceptions = [];
foreach ($lines as $line) {
    if (str_contains($line, 'local.ERROR') || str_contains($line, 'exception') || str_contains($line, 'Exception') || str_contains($line, 'SQLSTATE')) {
        $exceptions[] = $line;
    }
}
echo "Total log errors found: " . count($exceptions) . "\n";
print_r(array_slice($exceptions, -15));
