<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Correction de l'affichage des images:\n";
echo "========================================\n";

// 1. Vérifier le lien symbolique storage
echo "1. Vérification du lien symbolique storage:\n";
$storageLink = public_path('storage');
if (is_link($storageLink)) {
    echo "   ✅ Lien symbolique storage existe\n";
    echo "   📁 Pointe vers: " . readlink($storageLink) . "\n";
} else {
    echo "   ❌ Lien symbolique storage manquant\n";
    echo "   🔧 Création du lien symbolique...\n";
    
    try {
        // Créer le lien symbolique
        if (symlink(storage_path('app/public'), $storageLink)) {
            echo "   ✅ Lien symbolique créé avec succès\n";
        } else {
            echo "   ❌ Échec de création du lien symbolique\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    }
}

// 2. Vérifier les fichiers d'images
echo "\n2. Vérification des fichiers d'images:\n";
$participants = DB::table('participants')
    ->whereNotNull('passport_path')
    ->orWhereNotNull('photo_path')
    ->orWhereNotNull('cin_path')
    ->get();

foreach($participants as $participant) {
    echo "   Participant {$participant->id}: {$participant->first_name} {$participant->last_name}\n";
    
    if ($participant->photo_path) {
        $photoPath = storage_path('app/public/' . str_replace('/storage/', '', $participant->photo_path));
        echo "     - Photo: " . ($participant->photo_path) . "\n";
        echo "       Fichier existe: " . (file_exists($photoPath) ? '✅' : '❌') . "\n";
    }
    
    if ($participant->passport_path) {
        $passportPath = storage_path('app/public/' . str_replace('/storage/', '', $participant->passport_path));
        echo "     - Passeport: " . ($participant->passport_path) . "\n";
        echo "       Fichier existe: " . (file_exists($passportPath) ? '✅' : '❌') . "\n";
    }
    
    if ($participant->cin_path) {
        $cinPath = storage_path('app/public/' . str_replace('/storage/', '', $participant->cin_path));
        echo "     - CIN: " . ($participant->cin_path) . "\n";
        echo "       Fichier existe: " . (file_exists($cinPath) ? '✅' : '❌') . "\n";
    }
}

// 3. Tester l'accès aux images via HTTP
echo "\n3. Test d'accès HTTP aux images:\n";
$testParticipant = DB::table('participants')
    ->whereNotNull('passport_path')
    ->first();

if ($testParticipant && $testParticipant->passport_path) {
    $imageUrl = 'http://localhost:8000' . $testParticipant->passport_path;
    echo "   URL test: $imageUrl\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'HEAD',
            'timeout' => 5
        ]
    ]);
    
    $headers = @get_headers($imageUrl, 1, $context);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "   ✅ Image accessible via HTTP\n";
    } else {
        echo "   ❌ Image non accessible via HTTP\n";
        echo "   Headers: " . ($headers ? implode(', ', $headers) : 'Aucun') . "\n";
    }
}

// 4. Vérifier la configuration Laravel
echo "\n4. Configuration Laravel:\n";
echo "   Storage path: " . storage_path('app/public') . "\n";
echo "   Public path: " . public_path() . "\n";
echo "   Storage link: " . public_path('storage') . "\n";

// 5. Instructions de correction
echo "\n5. Instructions de correction:\n";
echo "   - Si le lien symbolique manque: php artisan storage:link\n";
echo "   - Si les fichiers n'existent pas: Vérifiez les uploads\n";
echo "   - Si l'accès HTTP échoue: Vérifiez la configuration\n";
echo "   - Testez: http://localhost:8000/storage/participants/passports/...\n";
