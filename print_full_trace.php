<?php
if (!file_exists('storage/logs/laravel.log')) {
    echo "No log file found.\n";
    exit;
}
$log = file_get_contents('storage/logs/laravel.log');
// Find the last occurrence of "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'created_at'"
$pos = strrpos($log, "Column not found: 1054 Unknown column 'created_at'");
if ($pos === false) {
    echo "Error not found in log.\n";
    exit;
}
// Print 3000 characters from that position
echo substr($log, $pos - 200, 3000);
