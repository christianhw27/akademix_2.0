<?php
$sql = file_get_contents('akademix2.sql');
if (preg_match('/CREATE TABLE (?:IF NOT EXISTS )?`students`\s*\((.*?)\)\s*ENGINE/is', $sql, $match)) {
    echo "Table students:\n" . $match[0] . "\n";
} else {
    echo "students table definition not found\n";
}
