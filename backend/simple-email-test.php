<?php

// Test simple d'envoi d'email
echo "🧪 Test d'envoi d'email YouthConnekt Sahel 2025\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Configuration email manuelle
$to = 'souleymanemhamatsaleh2000@gmail.com';
$subject = 'Test Email YouthConnekt Sahel 2025';
$message = 'Ceci est un test d\'envoi d\'email pour vérifier la configuration.';
$headers = [
    'From: souleymanemhamatsaleh2000@gmail.com',
    'Reply-To: souleymanemhamatsaleh2000@gmail.com',
    'X-Mailer: PHP/' . phpversion(),
    'Content-Type: text/html; charset=UTF-8'
];

echo "📧 Envoi d'email vers: $to\n";
echo "📝 Sujet: $subject\n\n";

// Tentative d'envoi avec mail() PHP
if (mail($to, $subject, $message, implode("\r\n", $headers))) {
    echo "✅ Email envoyé avec succès via mail() PHP!\n";
} else {
    echo "❌ Échec de l'envoi via mail() PHP\n";
}

// Test avec PHPMailer si disponible
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "\n📧 Test avec PHPMailer...\n";
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'souleymanemhamatsaleh2000@gmail.com';
        $mail->Password = 'wqcy ottf dxzb';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        // Destinataires
        $mail->setFrom('souleymanemhamatsaleh2000@gmail.com', 'YouthConnekt Sahel 2025');
        $mail->addAddress($to);
        
        // Contenu
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = '<h1>Test Email YouthConnekt Sahel 2025</h1><p>Ceci est un test d\'envoi d\'email avec PHPMailer.</p>';
        
        $mail->send();
        echo "✅ Email envoyé avec succès via PHPMailer!\n";
    } catch (Exception $e) {
        echo "❌ Erreur PHPMailer: " . $e->getMessage() . "\n";
    }
} else {
    echo "ℹ️ PHPMailer non disponible\n";
}

echo "\n🎉 Test terminé!\n";
echo "📧 Vérifiez votre boîte email: $to\n";

