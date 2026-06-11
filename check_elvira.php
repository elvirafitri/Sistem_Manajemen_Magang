<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::where('name', 'like', '%elvira%')->get(['id', 'name', 'role', 'avatar_path'])->toArray();
echo json_encode($users, JSON_PRETTY_PRINT);
