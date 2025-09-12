<?php
$path = __DIR__ . '/../database/database.sqlite';
if (!file_exists($path)) {
    echo "MISSING_DB\n";
    exit(1);
}
try{
    $db = new SQLite3($path, SQLITE3_OPEN_READONLY);
}catch(Exception $e){
    echo "OPEN_ERR: " . $e->getMessage() . "\n";
    exit(2);
}
$res = $db->query('SELECT id, first_name, last_name, email, country, city, status, created_at FROM participants ORDER BY id DESC LIMIT 50');
$rows = [];
while($r = $res->fetchArray(SQLITE3_ASSOC)){
    $rows[] = $r;
}
if(count($rows) === 0){
    echo "NO_ROWS\n";
    exit(0);
}
foreach($rows as $row){
    echo json_encode($row, JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
