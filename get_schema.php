<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schema = [];
$tables = Illuminate\Support\Facades\Schema::getTables();
foreach ($tables as $t) {
    $table = $t['name'];
    $columns = Illuminate\Support\Facades\Schema::getColumns($table);
    $schema[$table] = array_map(function($c) { return $c['name']; }, $columns);
}
echo json_encode($schema, JSON_PRETTY_PRINT);
