<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation de stand - YouthConnekt Sahel 2025</title>
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
            border-bottom: 3px solid #2196F3;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #2196F3, #03A9F4);
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
            color: #2196F3;
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
            color: #2196F3;
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
            border-left: 4px solid #2196F3;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #2196F3;
        }
        .stand-info {
            background: linear-gradient(135deg, #2196F3, #03A9F4);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        .stand-info h3 {
            margin-top: 0;
            color: white;
        }
        .stand-info ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        .stand-info li {
            margin-bottom: 8px;
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
            color: #2196F3;
            text-decoration: none;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(45deg, #2196F3, #03A9F4);
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
            <div class="logo">🏪</div>
            <h1 class="title">Réservation de Stand</h1>
            <p class="subtitle">YouthConnekt Sahel 2025 • 13-15 Octobre 2025 • N'Djamena, Tchad</p>
        </div>

        <div class="content">
            <div class="greeting">
                Bonjour {{ $standData['contactPerson'] }},
            </div>

            <div class="message">
                Nous avons bien reçu votre demande de réservation de stand pour le Forum YouthConnekt Sahel 2025. 
                Nous vous remercions pour votre intérêt à participer à cet événement exceptionnel qui réunira 
                plus de 2000 participants du Sahel.
            </div>

            <div class="details">
                <h3 style="color: #2196F3; margin-top: 0;">Détails de votre réservation :</h3>
                <div class="detail-item">
                    <span class="detail-label">Entreprise :</span> {{ $standData['companyName'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Type de stand :</span> {{ $standData['standType'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Taille :</span> {{ $standData['standSize'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pays :</span> {{ $standData['country'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email :</span> {{ $standData['email'] }}
                </div>
                @if(isset($standData['budget']) && $standData['budget'])
                <div class="detail-item">
                    <span class="detail-label">Budget :</span> {{ $standData['budget'] }} USD
                </div>
                @endif
            </div>

            <div class="stand-info">
                <h3>Votre stand inclut :</h3>
                <ul>
                    <li>Espace d'exposition dédié selon votre sélection</li>
                    <li>Table et chaises de base</li>
                    <li>Éclairage et électricité</li>
                    <li>Accès aux visiteurs du forum</li>
                    <li>Support technique sur place</li>
                    @if(isset($standData['additionalServices']) && $standData['additionalServices'])
                    <li>Services additionnels : {{ $standData['additionalServices'] }}</li>
                    @endif
                </ul>
            </div>

            <div class="message">
                Notre équipe examinera votre demande dans les 24 heures et vous contactera pour confirmer 
                la disponibilité et finaliser les détails de votre stand. Vous recevrez également 
                un guide complet pour préparer votre exposition.
            </div>

            <div style="text-align: center;">
                <a href="http://localhost:3000/exhibitions" class="cta-button">
                    En savoir plus sur les expositions
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


