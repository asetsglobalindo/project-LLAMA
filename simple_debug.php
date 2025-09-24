<?php

$message = "Untuk lokasi di Jakarta dengan budget sekitar 100.000 per bulan";
$lower = mb_strtolower(trim($message), 'UTF-8');

echo "Original: $message\n";
echo "Lowercase: $lower\n";

$knownCities = ['jakarta','bogor','depok','tangerang','bandung'];
$city = null;

foreach ($knownCities as $c) {
    if (str_contains($lower, $c)) { 
        $city = ucfirst($c); 
        echo "Found city: $city\n";
        break; 
    }
}

if (!$city) {
    echo "No city detected!\n";
}

// Test budget extraction
if (preg_match('/(\d+)\s*(ribu|rb)/i', $message, $m)) {
    $value = ((int)$m[1]) * 1000;
    echo "Budget detected: $value\n";
}
