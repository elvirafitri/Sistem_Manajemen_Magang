<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PesertaProfile;
use App\Services\CertificateService;

$peserta = PesertaProfile::first();
if (!$peserta) {
    echo "No peserta found\n";
    exit;
}

$service = app(CertificateService::class);
$cert = $service->generateOrRefresh($peserta);

echo "Certificate generated: " . $cert->file_path . "\n";
