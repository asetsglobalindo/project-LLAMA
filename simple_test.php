<?php

function detectLanguage(string $message): string
{
    $lower = mb_strtolower(trim($message), 'UTF-8');
    
    // Kata kunci bahasa Indonesia
    $indonesianKeywords = [
        'halo', 'hai', 'selamat', 'pagi', 'siang', 'sore', 'malam',
        'saya', 'aku', 'saya mau', 'saya cari', 'saya butuh',
        'ruang', 'lokasi', 'tempat', 'area',
        'budget', 'anggaran', 'harga', 'biaya', 'per bulan',
        'ukuran', 'besar', 'kecil', 'meter', 'm²', 'm2',
        'juta', 'jt', 'ribu', 'rb', 'rupiah',
        'buka', 'usaha', 'toko', 'restoran', 'cafe',
        'cocok', 'sesuai', 'rekomendasi', 'saran',
        'bisa', 'boleh', 'mohon', 'tolong'
    ];
    
    // Kata kunci bahasa Inggris
    $englishKeywords = [
        'hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening',
        'i', 'i want', 'i need', 'i am looking', 'i am searching',
        'location', 'place', 'area', 'commercial',
        'price', 'cost', 'per month', 'monthly',
        'size', 'big', 'small', 'square meter', 'sqm', 'sq m',
        'million', 'thousand', 'k', 'dollar', 'usd',
        'open', 'business', 'store', 'restaurant', 'cafe',
        'suitable', 'recommend', 'suggestion', 'advice',
        'can', 'could', 'please', 'help', 'assist'
    ];
    
    $indonesianCount = 0;
    $englishCount = 0;
    
    // Hitung kata kunci Indonesia
    foreach ($indonesianKeywords as $keyword) {
        if (str_contains($lower, $keyword)) {
            $indonesianCount++;
        }
    }
    
    // Hitung kata kunci Inggris
    foreach ($englishKeywords as $keyword) {
        if (str_contains($lower, $keyword)) {
            $englishCount++;
        }
    }
    
    // Jika ada kata kunci yang jelas, gunakan yang lebih banyak
    if ($indonesianCount > 0 || $englishCount > 0) {
        return $indonesianCount >= $englishCount ? 'id' : 'en';
    }
    
    // Deteksi berdasarkan pola kata-kata umum
    $hasIndonesianPattern = preg_match('/\b(saya|aku|mau|cari|butuh|bisa|boleh|tolong|mohon)\b/u', $lower);
    $hasEnglishPattern = preg_match('/\b(i|am|want|need|looking|searching|can|could|please|help)\b/u', $lower);
    
    if ($hasIndonesianPattern && !$hasEnglishPattern) {
        return 'id';
    }
    
    if ($hasEnglishPattern && !$hasIndonesianPattern) {
        return 'en';
    }
    
    // Deteksi berdasarkan kata sambung dan preposisi
    $hasIndonesianConnectors = preg_match('/\b(di|untuk|dengan|dari|ke|pada|dalam)\b/u', $lower);
    $hasEnglishConnectors = preg_match('/\b(in|for|with|from|to|at|on|by)\b/u', $lower);
    
    if ($hasIndonesianConnectors && !$hasEnglishConnectors) {
        return 'id';
    }
    
    if ($hasEnglishConnectors && !$hasIndonesianConnectors) {
        return 'en';
    }
    
    // Default ke Indonesia jika tidak bisa dideteksi
    return 'id';
}

// Test cases
$testCases = [
    'Halo, saya mau cari ruang di Jakarta' => 'id',
    'Hello, I am looking for space in Jakarta' => 'en',
    'Jakarta' => 'id',
    'Space' => 'en',
];

echo "Testing Language Detection:\n";
echo "========================\n\n";

foreach ($testCases as $input => $expected) {
    $result = detectLanguage($input);
    $status = $result === $expected ? '✓' : '✗';
    echo "{$status} Input: \"{$input}\"\n";
    echo "   Expected: {$expected}, Got: {$result}\n\n";
}
