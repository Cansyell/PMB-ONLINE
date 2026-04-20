<?php
// test-register.php

$baseUrl    = 'http://localhost:8000';
$registerUrl = $baseUrl . '/register';
$totalUsers  = 100;

function getCsrfToken($url, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookieFile); // simpan cookie
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile); // baca cookie
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $html = curl_exec($ch);
    curl_close($ch);

    preg_match('/<input[^>]+name="_token"[^>]+value="([^"]+)"/', $html, $matches);
    return $matches[1] ?? null;
}

$multiHandle = curl_multi_init();
$handles     = [];

for ($i = 1; $i <= $totalUsers; $i++) {
    // Tiap user punya cookie file sendiri
    $cookieFile = sys_get_temp_dir() . "/laravel_cookie_{$i}.txt";
    
    // Ambil token + cookie untuk user ini
    $token = getCsrfToken($registerUrl, $cookieFile);

    if (!$token) {
        echo "User {$i}: Token tidak ditemukan!\n";
        continue;
    }

    $email    = "testuser{$i}_" . time() . "@test.com";
    $postData = http_build_query([
        '_token'                => $token,
        'name'                  => "Test User {$i}",
        'email'                 => $email,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $ch = curl_init($registerUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST,           true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $postData);
    curl_setopt($ch, CURLOPT_COOKIEJAR,      $cookieFile); // cookie yang sama!
    curl_setopt($ch, CURLOPT_COOKIEFILE,     $cookieFile); // cookie yang sama!
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT,      'Mozilla/5.0');

    curl_multi_add_handle($multiHandle, $ch);
    $handles[$i] = ['handle' => $ch, 'email' => $email, 'token' => $token];
}

// Jalankan semua bersamaan
$running = null;
do {
    curl_multi_exec($multiHandle, $running);
    curl_multi_select($multiHandle);
} while ($running > 0);

// Cek hasil
echo "\n====== HASIL REGISTER ======\n";
$berhasil = 0;
$gagal    = 0;

foreach ($handles as $i => $data) {
    $response = curl_multi_getcontent($data['handle']);
    $httpCode = curl_getinfo($data['handle'], CURLINFO_HTTP_CODE);
    $finalUrl = curl_getinfo($data['handle'], CURLINFO_EFFECTIVE_URL);

    // 302 = redirect (berhasil), 200 = mungkin error validasi
    if ($httpCode === 302 || str_contains($finalUrl, '/dashboard') || str_contains($finalUrl, '/home')) {
        $status = '✓ BERHASIL';
        $berhasil++;
    } else {
        $status = '✗ GAGAL';
        $gagal++;

        // Tampilkan error dari response HTML
        preg_match('/<div[^>]+class="[^"]*text-red[^"]*"[^>]*>(.*?)<\/div>/s', $response, $errMatch);
        $errorMsg = isset($errMatch[1]) ? trim(strip_tags($errMatch[1])) : 'cek manual';
        echo "User {$i} | {$status} | HTTP {$httpCode} | Error: {$errorMsg}\n";
        continue;
    }

    echo "User {$i} | {$status} | HTTP {$httpCode} | {$data['email']}\n";

    curl_multi_remove_handle($multiHandle, $data['handle']);
    curl_close($data['handle']);
}

curl_multi_close($multiHandle);

echo "\n====== SUMMARY ======\n";
echo "Total    : {$totalUsers}\n";
echo "Berhasil : {$berhasil}\n";
echo "Gagal    : {$gagal}\n";