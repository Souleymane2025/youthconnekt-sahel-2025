<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de candidature - Sponsor YouthConnekt Sahel 2025</title>
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
            border-bottom: 3px solid #FFD700;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #FFD700, #FFA726);
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
            color: #FFD700;
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
            color: #FFD700;
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
            border-left: 4px solid #FFD700;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #FFD700;
        }
        .benefits {
            background: linear-gradient(135deg, #FFD700, #FFA726);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        .benefits h3 {
            margin-top: 0;
            color: white;
        }
        .benefits ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        .benefits li {
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
            color: #FFD700;
            text-decoration: none;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(45deg, #FFD700, #FFA726);
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
            <div class="logo">💰</div>
            <h1 class="title">Sponsor YouthConnekt Sahel 2025</h1>
            <p class="subtitle">2ème Édition • 13-15 Octobre 2025 • N'Djamena, Tchad</p>
        </div>

        <div class="content">
            <div class="greeting">
                Bonjour {{ $partnerData['contactPerson'] }},
            </div>

            <div class="message">
                Nous avons bien reçu votre candidature en tant que <strong>Sponsor</strong> 
                pour le Forum YouthConnekt Sahel 2025. Nous vous remercions pour votre engagement 
                envers l'émancipation de la jeunesse sahélo-saharienne et votre soutien financier.
            </div>

            <div class="details">
                <h3 style="color: #FFD700; margin-top: 0;">Détails de votre candidature :</h3>
                <div class="detail-item">
                    <span class="detail-label">Entreprise :</span> {{ $partnerData['companyName'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Niveau de sponsoring :</span> {{ $partnerData['sponsorshipLevel'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pays :</span> {{ $partnerData['country'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email :</span> {{ $partnerData['email'] }}
                </div>
                @if(isset($partnerData['budget']) && $partnerData['budget'])
                <div class="detail-item">
                    <span class="detail-label">Budget estimé :</span> {{ $partnerData['budget'] }} USD
                </div>
                @endif
            </div>

            <div class="benefits">
                <h3>Avantages de votre sponsoring :</h3>
                <ul>
                    <li>Visibilité exceptionnelle auprès de 2000+ participants</li>
                    <li>Accès privilégié aux décideurs et leaders du Sahel</li>
                    <li>Exposition sur tous nos supports de communication</li>
                    <li>Participation aux événements de networking exclusifs</li>
                    <li>Impact social mesurable sur le développement du Sahel</li>
                </ul>
            </div>

            <div class="message">
                Notre équipe examinera votre candidature dans les 48 heures et vous contactera 
                pour discuter des détails du sponsoring et des avantages spécifiques à votre niveau.
            </div>

            <div style="text-align: center;">
                <a href="http://localhost:3000/sponsors" class="cta-button">
                    Découvrir nos sponsors
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

