<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔄 Exécution des migrations de la base de données...\n";

try {
    // Exécuter les migrations
    Artisan::call('migrate', ['--force' => true]);
    
    echo "✅ Migrations exécutées avec succès!\n";
    
    // Afficher le statut des migrations
    Artisan::call('migrate:status');
    echo Artisan::output();
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'exécution des migrations: " . $e->getMessage() . "\n";
    exit(1);
}

echo "🎉 Base de données mise à jour avec succès!\n";

