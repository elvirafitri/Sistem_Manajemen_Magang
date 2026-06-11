<?php
$fonts = [
    'Montserrat-SemiBold.ttf' => 'https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap',
];
$ctx = stream_context_create(['http'=>['timeout'=>15,'user_agent'=>'Mozilla/5.0 (compatible; Googlebot/2.1)']]);
foreach ($fonts as $filename => $cssUrl) {
    $css = @file_get_contents($cssUrl, false, $ctx);
    preg_match('/url\((https:\/\/fonts\.gstatic\.com[^)]+\.ttf)\)/', $css ?? '', $m);
    $url = $m[1] ?? '';
    if (!$url) { preg_match('/src: url\((https:\/\/fonts\.gstatic\.com[^)]+)\)/', $css ?? '', $m); $url = $m[1] ?? ''; }
    if ($url) {
        $data = file_get_contents($url, false, $ctx);
        if ($data && strlen($data) > 10000) {
            file_put_contents(__DIR__ . '/public/fonts/' . $filename, $data);
            echo "OK: $filename - " . strlen($data) . " bytes\n";
        } else { echo "FAIL: $filename\n"; }
    } else { echo "NO URL: $filename\n"; }
}
