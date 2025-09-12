<?php
$path = __DIR__ . '/../storage/pending_participants.json';
echo "path={$path}\n";
if(!file_exists($path)){
    echo "exists=0\n";
    exit(0);
}
echo "exists=1\n";
$size = filesize($path);
echo "size={$size}\n";
$raw = file_get_contents($path);
echo "len_raw=".strlen($raw) . "\n";
$sample = substr($raw,0,1000);
echo "sample=\n". $sample . "\n---end-sample---\n";
$j = json_decode($raw, true);
$err = json_last_error();
$errmsg = json_last_error_msg();
echo "decoded_count=" . (is_array($j)?count($j):'NA') . "\n";
echo "json_err={$err} {$errmsg}\n";
if(is_array($j)){
    echo "first_item_preview=\n" . json_encode($j[0], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) . "\n";
}
