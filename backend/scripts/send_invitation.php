<?php
/**
 * Script pour envoyer une invitation officielle à un participant
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

// Fonction pour envoyer un email d'invitation
function sendInvitationEmail($participant) {
    $subject = "🎉 Invitation Officielle - YouthConnekt Sahel 2025";
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #2E7D32, #4CAF50); color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .highlight { background: #f8f9fa; padding: 15px; border-left: 4px solid #2E7D32; margin: 15px 0; }
            .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
            .btn { background: #2E7D32; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>🎉 YouthConnekt Sahel 2025</h1>
            <p>Invitation Officielle</p>
        </div>
        
        <div class='content'>
            <h2>Cher(e) {$participant['first_name']} {$participant['last_name']},</h2>
            
            <p>Nous avons le plaisir de vous confirmer votre inscription au <strong>Forum YouthConnekt Sahel 2025</strong> qui se tiendra du <strong>13 au 15 Octobre 2025</strong> à <strong>N'Djamena, Tchad</strong>.</p>
            
            <div class='highlight'>
                <h3>📋 Détails de votre inscription:</h3>
                <ul>
                    <li><strong>Nom:</strong> {$participant['first_name']} {$participant['last_name']}</li>
                    <li><strong>Email:</strong> {$participant['email']}</li>
                    <li><strong>Pays:</strong> {$participant['country']}</li>
                    <li><strong>Ville:</strong> {$participant['city']}</li>
                    <li><strong>Statut:</strong> {$participant['occupation']}</li>
                    <li><strong>Organisation:</strong> " . ($participant['organization'] ?? 'Non spécifié') . "</li>
                </ul>
            </div>
            
            <h3>🎯 Prochaines étapes:</h3>
            <ol>
                <li><strong>Confirmation:</strong> Votre inscription a été validée</li>
                <li><strong>Badge:</strong> Vous recevrez votre badge numérique dans les prochains jours</li>
                <li><strong>Programme:</strong> Le programme détaillé sera disponible sur notre site</li>
                <li><strong>Logistique:</strong> Les informations pratiques vous seront envoyées</li>
            </ol>
            
            <h3>📅 Informations importantes:</h3>
            <ul>
                <li><strong>Dates:</strong> 13-15 Octobre 2025</li>
                <li><strong>Lieu:</strong> N'Djamena, Tchad</li>
                <li><strong>Thème:</strong> Connectons, innovons et transformons ensemble l'avenir du Sahel</li>
            </ul>
            
            <p>Nous sommes ravis de vous accueillir à cet événement historique qui rassemblera plus de 2000 jeunes leaders du Sahel.</p>
            
            <p>Pour toute question, n'hésitez pas à nous contacter.</p>
            
            <p>Cordialement,<br>
            <strong>L'équipe YouthConnekt Sahel 2025</strong></p>
        </div>
        
        <div class='footer'>
            <p>YouthConnekt Sahel 2025 | N'Djamena, Tchad | 13-15 Octobre 2025</p>
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        </div>
    </body>
    </html>";
    
    return [
        'subject' => $subject,
        'message' => $message,
        'to' => $participant['email'],
        'participant_name' => $participant['first_name'] . ' ' . $participant['last_name']
    ];
}

// Fonction pour simuler l'envoi d'email (à remplacer par un vrai service d'email)
function simulateEmailSending($emailData) {
    // Ici vous pouvez intégrer un vrai service d'email comme SendGrid, Mailgun, etc.
    echo "📧 Email simulé envoyé à: {$emailData['to']}\n";
    echo "📋 Sujet: {$emailData['subject']}\n";
    echo "👤 Destinataire: {$emailData['participant_name']}\n";
    echo "✅ Email envoyé avec succès!\n\n";
    
    return true;
}

// Script principal
try {
    $participantId = $argv[1] ?? null;
    
    if (!$participantId) {
        echo "❌ Usage: php send_invitation.php <participant_id>\n";
        echo "📝 Exemple: php send_invitation.php 1\n";
        exit(1);
    }
    
    echo "🔍 Recherche du participant ID: $participantId\n";
    
    // Récupérer le participant
    $participant = DB::table('participants')->where('id', $participantId)->first();
    
    if (!$participant) {
        echo "❌ Participant non trouvé avec l'ID: $participantId\n";
        exit(1);
    }
    
    echo "✅ Participant trouvé: {$participant->first_name} {$participant->last_name}\n";
    echo "📧 Email: {$participant->email}\n";
    
    // Générer l'email d'invitation
    $emailData = sendInvitationEmail((array)$participant);
    
    // Envoyer l'email
    $success = simulateEmailSending($emailData);
    
    if ($success) {
        // Mettre à jour le statut du participant
        DB::table('participants')
            ->where('id', $participantId)
            ->update([
                'status' => 'invited',
                'updated_at' => now()
            ]);
        
        echo "🎉 Invitation envoyée avec succès!\n";
        echo "📊 Statut du participant mis à jour: invited\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
