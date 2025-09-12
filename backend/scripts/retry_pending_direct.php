<?php
/**
 * Direct retry script.
 * Reads storage/pending_participants.json and inserts into SQLite DB using PDO.
 * Logs to storage/logs/retry_direct.log
 * Usage: php scripts/retry_pending_direct.php
 */
$root = __DIR__ . '/../';
$pendingPath = $root . 'storage/pending_participants.json';
$logPath = $root . 'storage/logs/retry_direct.log';
$dbPath = $root . 'database/database.sqlite';

function logline($msg){ global $logPath; $line = '['.date('Y-m-d H:i:s').'] '.$msg.PHP_EOL; file_put_contents($logPath, $line, FILE_APPEND); }

logline("Starting retry_direct.php");
if(!file_exists($pendingPath)){
    logline("No pending file at $pendingPath");
    echo "No pending file\n";
    exit(0);
}

// read with shared lock
$fp = fopen($pendingPath, 'r');
if ($fp === false) {
    logline("Failed to open pending file for reading: $pendingPath");
    echo "Failed to open pending file\n";
    exit(1);
}
if (!flock($fp, LOCK_SH)) {
    logline("Failed to obtain shared lock on $pendingPath");
    fclose($fp);
    exit(1);
}
$raw = stream_get_contents($fp);

// try to decode; if malformed UTF-8 or decode fails, attempt automatic repair
$pending = json_decode($raw ?: '[]', true);
if(!is_array($pending)){
    $err = json_last_error();
    $msg = json_last_error_msg();
    logline("Initial JSON decode failed: {$err} {$msg} - attempting auto-repair");
    // backup original
    @copy($pendingPath, $pendingPath . '.bak.' . time());
    // remove BOM if present
    $fixed = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
    // force-valid UTF-8 by ignoring invalid sequences
    if(function_exists('iconv')){
        $tmp = @iconv('UTF-8', 'UTF-8//IGNORE', $fixed);
        if($tmp !== false) $fixed = $tmp;
    }
    // strip non-printable control chars except common whitespace
    $fixed = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $fixed);
    // write repaired file atomically
    $tmpfile = $pendingPath . '.' . uniqid('repair_tmp_', true);
    @file_put_contents($tmpfile, $fixed);
    @rename($tmpfile, $pendingPath);
    // try decode again
    $pending = json_decode($fixed ?: '[]', true) ?: [];
    $err2 = json_last_error();
    logline("Auto-repair result: json_err={$err2} " . json_last_error_msg());
}
flock($fp, LOCK_UN);
fclose($fp);
if(!count($pending)){
    logline("Pending file empty");
    echo "Pending empty\n";
    exit(0);
}
if(!file_exists($dbPath)){
    logline("SQLite DB not found at $dbPath");
    echo "DB missing: $dbPath\n";
    exit(1);
}
try{
    $pdo = new PDO('sqlite:'.$dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Throwable $e){
    logline('PDO connect failed: '.$e->getMessage());
    echo 'PDO connect failed: '.$e->getMessage()."\n";
    exit(1);
}
// Ensure participants table exists. Create minimal schema if missing so recovery can run without migrations.
try{
    $res = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='participants'")->fetchAll();
    if(!count($res)){
        logline('participants table missing: creating minimal table');
        $create = <<<SQL
CREATE TABLE participants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT,
    last_name TEXT,
    email TEXT UNIQUE,
    phone TEXT,
    country TEXT,
    city TEXT,
    type TEXT,
    organization TEXT,
    occupation TEXT,
    interests TEXT,
    experience INTEGER,
    motivation TEXT,
    status TEXT DEFAULT 'pending',
    created_at TEXT,
    updated_at TEXT
);
SQL;
        $pdo->exec($create);
        logline('Created participants table');
    }
} catch (Throwable $e){
    logline('Failed to ensure participants table exists: '.$e->getMessage());
    echo 'Failed to ensure participants table exists: '.$e->getMessage()."\n";
    exit(1);
}
$insertSql = "INSERT INTO participants (first_name,last_name,email,phone,country,city,type,organization,occupation,interests,experience,motivation,status,created_at,updated_at) VALUES (:first_name,:last_name,:email,:phone,:country,:city,:type,:organization,:occupation,:interests,:experience,:motivation,:status,:created_at,:updated_at)";
$insert = $pdo->prepare($insertSql);
$success = [];
$failed = [];
foreach($pending as $i => $p){
    // if email already exists in DB, skip insertion and treat as success
    $email = $p['email'] ?? null;
    if ($email) {
        try{
            $check = $pdo->prepare("SELECT COUNT(*) as c FROM participants WHERE email = :email");
            $check->execute([':email' => $email]);
            $cnt = $check->fetchColumn();
            if($cnt > 0){
                $success[] = $email;
                logline("Skipped queued participant (already exists): $email");
                continue;
            }
        } catch (Throwable $e) {
            // if check fails, proceed to attempt insert and let it error
            logline('Email existence check failed: '.$e->getMessage());
        }
    }
    try {
        $insert->execute([
            ':first_name' => $p['first_name'] ?? null,
            ':last_name' => $p['last_name'] ?? null,
            ':email' => $p['email'] ?? null,
            ':phone' => $p['phone'] ?? null,
            ':country' => $p['country'] ?? null,
            ':city' => $p['city'] ?? null,
            ':type' => $p['type'] ?? null,
            ':organization' => $p['organization'] ?? null,
            ':occupation' => $p['occupation'] ?? null,
            ':interests' => isset($p['interests']) ? json_encode($p['interests'], JSON_UNESCAPED_UNICODE) : null,
            ':experience' => $p['experience'] ?? null,
            ':motivation' => $p['motivation'] ?? null,
            ':status' => $p['status'] ?? 'pending',
            ':created_at' => $p['queued_at'] ?? date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s'),
        ]);
        $success[] = $p['email'] ?? "(no-email)";
        logline("Inserted queued participant: " . ($p['email'] ?? '(no-email)'));
    } catch (Throwable $e){
        $failed[] = ['email'=>$p['email'] ?? null,'error'=>$e->getMessage()];
        logline("Failed to insert queued participant: " . ($p['email'] ?? '(no-email)') . ' - ' . $e->getMessage());
    }
}
// rewrite pending file with failures only (atomic)
$remaining = array_values(array_filter($pending, function($p) use ($failed){ foreach($failed as $f){ if(isset($p['email']) && $p['email'] === $f['email']) return true;} return false;}));
$tmp = $pendingPath . '.' . uniqid('tmp_', true);
file_put_contents($tmp, json_encode($remaining, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
@rename($tmp, $pendingPath);
logline('Done. Success: '.count($success).', Failed: '.count($failed));
echo "Done. Success: ".count($success)." Failed: ".count($failed)."\n";

