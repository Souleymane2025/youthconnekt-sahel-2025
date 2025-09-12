<?php
$path = __DIR__ . '/../storage/pending_participants.json';
$bak = $path . '.bak.' . time();
if(!file_exists($path)){
    echo "No file: $path\n";
    exit(1);
}
copy($path, $bak);
$raw = file_get_contents($path);
// Remove BOM if present
$raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
// Try to convert to valid UTF-8 ignoring invalid sequences
$clean = iconv('UTF-8', 'UTF-8//IGNORE', $raw);
if($clean === false) $clean = mb_convert_encoding($raw, 'UTF-8', 'UTF-8');
// Also remove non-printable control characters except whitespace
$clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $clean);
file_put_contents($path . '.tmp', $clean);
rename($path . '.tmp', $path);
echo "Rewrote $path (backup at $bak)\n";
$decoded = json_decode($clean, true);
if(json_last_error() !== JSON_ERROR_NONE){
    echo "json_err=".json_last_error()." ".json_last_error_msg()."\n";
    exit(2);
}
echo "decoded_count=".(is_array($decoded)?count($decoded):0)."\n";
