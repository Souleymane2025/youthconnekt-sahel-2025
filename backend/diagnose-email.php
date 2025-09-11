<?php

// Diagnostic complet du système d'email
echo "🔍 Diagnostic du système d'email YouthConnekt Sahel 2025\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// 1. Vérification de l'environnement Laravel
echo "1️⃣ Vérification de l'environnement Laravel\n";
echo "Laravel Version: " . app()->version() . "\n";
echo "Environment: " . app()->environment() . "\n";
echo "Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF') . "\n\n";

// 2. Vérification de la configuration email
echo "2️⃣ Configuration email actuelle\n";
echo "Mail Driver: " . config('mail.default') . "\n";
echo "Mail Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Mail Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Mail Encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "Mail Username: " . config('mail.mailers.smtp.username') . "\n";
echo "Mail Password: " . (config('mail.mailers.smtp.password') ? '***configuré***' : '❌ NON CONFIGURÉ') . "\n";
echo "Mail From Address: " . config('mail.from.address') . "\n";
echo "Mail From Name: " . config('mail.from.name') . "\n\n";

// 3. Test de connexion SMTP
echo "3️⃣ Test de connexion SMTP\n";
try {
    $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
        config('mail.mailers.smtp.host'),
        config('mail.mailers.smtp.port'),
        config('mail.mailers.smtp.encryption') === 'tls'
    );
    
    $transport->setUsername(config('mail.mailers.smtp.username'));
    $transport->setPassword(config('mail.mailers.smtp.password'));
    
    // Test de connexion
    $transport->start();
    echo "✅ Connexion SMTP réussie\n";
    $transport->stop();
} catch (Exception $e) {
    echo "❌ Erreur de connexion SMTP: " . $e->getMessage() . "\n";
}

// 4. Test d'envoi d'email simple
echo "\n4️⃣ Test d'envoi d'email simple\n";
try {
    \Illuminate\Support\Facades\Mail::raw('Test de diagnostic email YouthConnekt Sahel 2025', function ($message) {
        $message->to('souleymanemhamatsaleh2000@gmail.com')
                ->subject('Test Diagnostic - ' . date('Y-m-d H:i:s'));
    });
    echo "✅ Email de test envoyé avec succès\n";
} catch (Exception $e) {
    echo "❌ Erreur envoi email: " . $e->getMessage() . "\n";
    echo "Détails: " . $e->getTraceAsString() . "\n";
}

// 5. Vérification des logs
echo "\n5️⃣ Vérification des logs récents\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $recentLogs = array_slice(explode("\n", $logs), -10);
    echo "Dernières entrées de log:\n";
    foreach ($recentLogs as $log) {
        if (trim($log)) {
            echo "  " . $log . "\n";
        }
    }
} else {
    echo "❌ Fichier de log non trouvé\n";
}

// 6. Recommandations
echo "\n6️⃣ Recommandations\n";
echo "🔧 Solutions possibles:\n";
echo "1. Vérifiez que l'authentification à 2 facteurs est activée sur Gmail\n";
echo "2. Générez un nouveau mot de passe d'application Gmail\n";
echo "3. Vérifiez que le compte Gmail autorise les applications moins sécurisées\n";
echo "4. Utilisez un service d'email professionnel (SendGrid, Mailgun)\n";
echo "5. Configurez Mailtrap pour les tests\n\n";

echo "🔗 Liens utiles:\n";
echo "- Gmail App Passwords: https://myaccount.google.com/apppasswords\n";
echo "- Gmail Security: https://myaccount.google.com/security\n";
echo "- SendGrid: https://sendgrid.com/\n";
echo "- Mailtrap: https://mailtrap.io/\n\n";

echo "🎯 Action immédiate recommandée:\n";
echo "1. Allez sur https://myaccount.google.com/apppasswords\n";
echo "2. Sélectionnez 'Mail' et 'Other (Custom name)'\n";
echo "3. Entrez 'YouthConnekt Sahel 2025'\n";
echo "4. Copiez le mot de passe généré (16 caractères)\n";
echo "5. Remplacez 'wqcy ottf dxzb' dans le fichier .env\n";
echo "6. Redémarrez le serveur Laravel\n\n";

echo "🎉 Diagnostic terminé!\n";


