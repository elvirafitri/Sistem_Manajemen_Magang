<?php
$dir = 'c:/xampp/htdocs/sistem-magang/database/migrations';
$files = scandir($dir);
$schema = [];
$currentTable = '';

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $content = file_get_contents("$dir/$file");
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (preg_match("/Schema::create\('([^']+)'/", $line, $matches)) {
                $currentTable = $matches[1];
                if (!isset($schema[$currentTable])) {
                    $schema[$currentTable] = [];
                }
            } elseif (preg_match("/Schema::table\('([^']+)'/", $line, $matches)) {
                $currentTable = $matches[1];
            } elseif ($currentTable && preg_match("/\\\$table->([a-zA-Z0-9_]+)\('([^']+)'/", $line, $matches)) {
                $method = $matches[1];
                $col = $matches[2];
                if (!in_array($method, ['dropColumn', 'dropForeign', 'dropUnique', 'dropIndex'])) {
                    if (!in_array($col, $schema[$currentTable])) {
                        $schema[$currentTable][] = $col;
                    }
                } else {
                    if (($key = array_search($col, $schema[$currentTable])) !== false) {
                        unset($schema[$currentTable][$key]);
                        $schema[$currentTable] = array_values($schema[$currentTable]);
                    }
                }
            } elseif ($currentTable && preg_match("/\\\$table->(id|timestamps|softDeletes|rememberToken)\(\)/", $line, $matches)) {
                $col = $matches[1];
                if ($col === 'id') {
                    array_unshift($schema[$currentTable], 'id');
                } elseif ($col === 'timestamps') {
                    $schema[$currentTable][] = 'created_at';
                    $schema[$currentTable][] = 'updated_at';
                } elseif ($col === 'softDeletes') {
                    $schema[$currentTable][] = 'deleted_at';
                } elseif ($col === 'rememberToken') {
                    $schema[$currentTable][] = 'remember_token';
                }
            }
        }
    }
}
print_r($schema);
