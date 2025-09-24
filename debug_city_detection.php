<?php

function extractBudgetInfo(string $message): array
{
    $lower = mb_strtolower($message, 'UTF-8');
    $direction = null; // 'min' atau 'max'
    if (str_contains($lower, 'minimal') || str_contains($lower, 'di atas') || str_contains($lower, '>=') || str_contains($lower, 'lebih dari')) {
        $direction = 'min';
    } elseif (str_contains($lower, 'maksimal') || str_contains($lower, 'di bawah') || str_contains($lower, '<=') || str_contains($lower, 'kurang dari')) {
        $direction = 'max';
    }

    $raw = null;
    $value = null;
    if (preg_match('/(\d+[\.,]?\d*)\s*(juta|jt)/i', $message, $m)) {
        $num = floatval(str_replace([',','.'], '', $m[1]));
        $value = $num * 1000000;
        $raw = trim($m[0]);
    } elseif (preg_match('/(\d+)\s*(ribu|rb)/i', $message, $m)) {
        $value = ((int)$m[1]) * 1000;
        $raw = trim($m[0]);
    } elseif (preg_match('/\b(\d{5,9})\b/', $message, $m)) {
        $value = (int)$m[1];
        $raw = trim($m[1]);
    } elseif (preg_match('/\b(\d{2,4})\b/', $message, $m)) {
        $value = ((int)$m[1]) * 1000;
        $raw = trim($m[1]) . ' ribu';
    }

    return ['value' => $value, 'direction' => $direction, 'raw' => $raw];
}

function testCityDetection() {
    $testMessage = "Untuk lokasi di Jakarta dengan budget sekitar 100.000 per bulan";
    
    echo "Testing city detection for: \"$testMessage\"\n";
    echo "==========================================\n\n";
    
    $lower = mb_strtolower(trim($testMessage), 'UTF-8');
    echo "Lowercase message: \"$lower\"\n\n";
    
    $knownCities = ['jakarta','bogor','depok','tangerang','bandung'];
    $city = null;
    foreach ($knownCities as $c) {
        if (str_contains($lower, $c)) { 
            $city = ucfirst($c); 
            echo "Found city: $city (from keyword: $c)\n";
            break; 
        }
    }
    
    if (!$city) {
        echo "No city detected!\n";
    }
    
    echo "\nBudget extraction:\n";
    $budgetInfo = extractBudgetInfo($testMessage);
    echo "Value: " . ($budgetInfo['value'] ?? 'null') . "\n";
    echo "Direction: " . ($budgetInfo['direction'] ?? 'null') . "\n";
    echo "Raw: " . ($budgetInfo['raw'] ?? 'null') . "\n";
}

testCityDetection();
