<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réception - YouthConnekt Sahel 2025</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4CAF50;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .title {
            color: #4CAF50;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        .content {
            margin: 30px 0;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #4CAF50;
        }
        .response-time {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        .response-time h3 {
            margin-top: 0;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #4CAF50;
            text-decoration: none;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            transition: transform 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📧</div>
            <h1 class="title">Message Reçu</h1>
            <p class="subtitle">YouthConnekt Sahel 2025 • 2ème Édition • N'Djamena, Tchad</p>
        </div>

        <div class="content">
            <div class="greeting">
                Bonjour {{ $messageData['name'] }},
            </div>

            <div class="message">
                Nous avons bien reçu votre message et nous vous remercions de nous avoir contactés. 
                Votre message a été transmis à notre équipe qui vous répondra dans les plus brefs délais.
            </div>

            <div class="details">
                <h3 style="color: #4CAF50; margin-top: 0;">Résumé de votre message :</h3>
                <div class="detail-item">
                    <span class="detail-label">Nom :</span> {{ $messageData['name'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email :</span> {{ $messageData['email'] }}
                </div>
                @if(isset($messageData['phone']) && $messageData['phone'])
                <div class="detail-item">
                    <span class="detail-label">Téléphone :</span> {{ $messageData['phone'] }}
                </div>
                @endif
                @if(isset($messageData['subject']) && $messageData['subject'])
                <div class="detail-item">
                    <span class="detail-label">Sujet :</span> {{ $messageData['subject'] }}
                </div>
                @endif
                <div class="detail-item">
                    <span class="detail-label">Message :</span> 
                    <div style="margin-top: 10px; padding: 10px; background: white; border-radius: 5px; font-style: italic;">
                        "{{ $messageData['message'] }}"
                    </div>
                </div>
            </div>

            <div class="response-time">
                <h3>Délai de réponse :</h3>
                <p>
                    Notre équipe s'engage à vous répondre dans un délai de <strong>24 à 48 heures</strong>. 
                    Pour les questions urgentes, vous pouvez nous appeler directement au 
                    <strong>+235 66 16 17 53</strong>.
                </p>
            </div>

            <div class="message">
                En attendant notre réponse, n'hésitez pas à explorer notre site web pour en savoir plus 
                sur le Forum YouthConnekt Sahel 2025 et nos différentes activités.
            </div>

            <div style="text-align: center;">
                <a href="http://localhost:3000" class="cta-button">
                    Visiter notre site web
                </a>
            </div>
        </div>

        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
                <a href="#">Instagram</a>
            </div>
            <p>
                <strong>YouthConnekt Sahel 2025</strong><br>
                Forum de la Jeunesse pour le développement durable du Sahel<br>
                N'Djamena, Tchad<br>
                Email: contact@youthconnekt.td<br>
                Téléphone: +235 66 16 17 53
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>


