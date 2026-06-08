<?php
$col = DB::select("SHOW COLUMNS FROM users WHERE Field = 'id'")[0];
echo "ID Type: " . $col->Type . "\n";
