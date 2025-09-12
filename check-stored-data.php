<?php

require_once __DIR__ . '/backend/vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "📊 Vérification des données stockées dans le système\n";
echo "==================================================\n\n";

try {
    // Participants
    $participantsCount = \App\Models\Participant::count();
    $participantsConfirmed = \App\Models\Participant::where('status', 'confirmed')->count();
    $participantsPending = \App\Models\Participant::where('status', 'pending')->count();
    $participantsNational = \App\Models\Participant::where('type', 'national')->count();
    $participantsInternational = \App\Models\Participant::where('type', 'international')->count();
    
    echo "👥 PARTICIPANTS:\n";
    echo "   Total: {$participantsCount}\n";
    echo "   Confirmés: {$participantsConfirmed}\n";
    echo "   En attente: {$participantsPending}\n";
    echo "   Nationaux: {$participantsNational}\n";
    echo "   Internationaux: {$participantsInternational}\n\n";
    
    // Blogs
    $blogsCount = \App\Models\Blog::count();
    $blogsPublished = \App\Models\Blog::where('status', 'published')->count();
    $blogsDraft = \App\Models\Blog::where('status', 'draft')->count();
    
    echo "📝 BLOGS:\n";
    echo "   Total: {$blogsCount}\n";
    echo "   Publiés: {$blogsPublished}\n";
    echo "   Brouillons: {$blogsDraft}\n\n";
    
    // Utilisateurs
    $usersCount = \App\Models\User::count();
    echo "👤 UTILISATEURS:\n";
    echo "   Total: {$usersCount}\n\n";
    
    // Détails des participants récents
    echo "📋 DERNIERS PARTICIPANTS INSCRITS:\n";
    $recentParticipants = \App\Models\Participant::latest()->take(5)->get();
    foreach ($recentParticipants as $participant) {
        echo "   • {$participant->first_name} {$participant->last_name} ({$participant->email}) - {$participant->country} - {$participant->status}\n";
    }
    
    echo "\n📈 STATISTIQUES PAR PAYS:\n";
    $countries = \App\Models\Participant::selectRaw('country, COUNT(*) as count')
        ->groupBy('country')
        ->orderBy('count', 'desc')
        ->get();
    
    foreach ($countries as $country) {
        echo "   {$country->country}: {$country->count} participants\n";
    }
    
    echo "\n📊 RÉSUMÉ:\n";
    echo "   Total des données: " . ($participantsCount + $blogsCount + $usersCount) . " enregistrements\n";
    echo "   Participants: {$participantsCount}\n";
    echo "   Blogs: {$blogsCount}\n";
    echo "   Utilisateurs: {$usersCount}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}







