<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class DiagnoseEmail extends Command
{
    protected $signature = 'email:diagnose';
    protected $description = 'Diagnostic complet du système d\'email';

    public function handle()
    {
        $this->info('🔍 Diagnostic du système d\'email YouthConnekt Sahel 2025');
        $this->line(str_repeat('=', 60));

        // 1. Vérification de l'environnement Laravel
        $this->info('1️⃣ Vérification de l\'environnement Laravel');
        $this->line('Laravel Version: ' . app()->version());
        $this->line('Environment: ' . app()->environment());
        $this->line('Debug Mode: ' . (config('app.debug') ? 'ON' : 'OFF'));
        $this->newLine();

        // 2. Vérification de la configuration email
        $this->info('2️⃣ Configuration email actuelle');
        $this->line('Mail Driver: ' . config('mail.default'));
        $this->line('Mail Host: ' . config('mail.mailers.smtp.host'));
        $this->line('Mail Port: ' . config('mail.mailers.smtp.port'));
        $this->line('Mail Encryption: ' . config('mail.mailers.smtp.encryption'));
        $this->line('Mail Username: ' . config('mail.mailers.smtp.username'));
        $this->line('Mail Password: ' . (config('mail.mailers.smtp.password') ? '***configuré***' : '❌ NON CONFIGURÉ'));
        $this->line('Mail From Address: ' . config('mail.from.address'));
        $this->line('Mail From Name: ' . config('mail.from.name'));
        $this->newLine();

        // 3. Test d'envoi d'email simple
        $this->info('3️⃣ Test d\'envoi d\'email simple');
        try {
            Mail::raw('Test de diagnostic email YouthConnekt Sahel 2025', function ($message) {
                $message->to('souleymanemhamatsaleh2000@gmail.com')
                        ->subject('Test Diagnostic - ' . date('Y-m-d H:i:s'));
            });
            $this->info('✅ Email de test envoyé avec succès');
        } catch (\Exception $e) {
            $this->error('❌ Erreur envoi email: ' . $e->getMessage());
        }

        // 4. Recommandations
        $this->newLine();
        $this->info('4️⃣ Recommandations');
        $this->line('🔧 Solutions possibles:');
        $this->line('1. Vérifiez que l\'authentification à 2 facteurs est activée sur Gmail');
        $this->line('2. Générez un nouveau mot de passe d\'application Gmail');
        $this->line('3. Vérifiez que le compte Gmail autorise les applications moins sécurisées');
        $this->line('4. Utilisez un service d\'email professionnel (SendGrid, Mailgun)');
        $this->line('5. Configurez Mailtrap pour les tests');
        $this->newLine();

        $this->line('🔗 Liens utiles:');
        $this->line('- Gmail App Passwords: https://myaccount.google.com/apppasswords');
        $this->line('- Gmail Security: https://myaccount.google.com/security');
        $this->line('- SendGrid: https://sendgrid.com/');
        $this->line('- Mailtrap: https://mailtrap.io/');
        $this->newLine();

        $this->info('🎯 Action immédiate recommandée:');
        $this->line('1. Allez sur https://myaccount.google.com/apppasswords');
        $this->line('2. Sélectionnez \'Mail\' et \'Other (Custom name)\'');
        $this->line('3. Entrez \'YouthConnekt Sahel 2025\'');
        $this->line('4. Copiez le mot de passe généré (16 caractères)');
        $this->line('5. Remplacez \'wqcy ottf dxzb\' dans le fichier .env');
        $this->line('6. Redémarrez le serveur Laravel');
        $this->newLine();

        $this->info('🎉 Diagnostic terminé!');
    }
}


