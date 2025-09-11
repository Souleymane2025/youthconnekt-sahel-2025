<?php
/**
 * Script pour supprimer tous les participants de test
 * YouthConnekt Sahel 2025
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Configuration de la base de données
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/../database/database.sqlite',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    echo "🗑️  Suppression de tous les participants de test...\n";
    
    // Compter les participants avant suppression
    $countBefore = DB::table('participants')->count();
    echo "📊 Nombre de participants avant suppression: $countBefore\n";
    
    if ($countBefore === 0) {
        echo "✅ Aucun participant à supprimer.\n";
        exit(0);
    }
    
    // Supprimer tous les participants
    $deleted = DB::table('participants')->delete();
    
    echo "✅ $deleted participants supprimés avec succès!\n";
    echo "🔄 Base de données réinitialisée pour un nouveau départ.\n";
    
    // Vérifier que la table est vide
    $countAfter = DB::table('participants')->count();
    echo "📊 Nombre de participants après suppression: $countAfter\n";
    
    if ($countAfter === 0) {
        echo "🎉 Opération réussie! La base de données est maintenant vide.\n";
    } else {
        echo "⚠️  Attention: Il reste $countAfter participants.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la suppression: " . $e->getMessage() . "\n";
    exit(1);
}
