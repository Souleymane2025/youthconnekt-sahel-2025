<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Officielle - YouthConnekt Sahel 2025</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #1e3c72;
            position: relative;
            z-index: 1;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .subtitle {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1e3c72;
        }
        .invitation-text {
            font-size: 16px;
            margin-bottom: 30px;
            text-align: justify;
        }
        .participant-info {
            background: #f8f9fa;
            border-left: 4px solid #1e3c72;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #1e3c72;
        }
        .info-value {
            flex: 1;
        }
        .event-details {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: 30px 0;
            border-radius: 10px;
            text-align: center;
        }
        .event-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .event-date {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .event-location {
            font-size: 16px;
            opacity: 0.9;
        }
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background: #1e3c72;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .footer-logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .footer-text {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 10px;
        }
        .contact-info {
            font-size: 12px;
            opacity: 0.7;
        }
        .highlight {
            background: linear-gradient(120deg, #a8edea 0%, #fed6e3 100%);
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">YC</div>
            <h1 class="title">YouthConnekt Sahel 2025</h1>
            <p class="subtitle">2ème Édition - Forum de la Jeunesse du Sahel</p>
        </div>

        <div class="content">
            <div class="greeting">
                Cher(e) <span class="highlight">{{ $participant['first_name'] }} {{ $participant['last_name'] }}</span>,
            </div>

            <div class="invitation-text">
                <p>Nous avons le plaisir de vous inviter officiellement au <strong>Forum YouthConnekt Sahel 2025</strong>, qui se tiendra sous le haut patronage du Président de la République du Tchad.</p>
                
                <p>Votre candidature a été <span class="highlight">approuvée</span> et nous sommes ravis de vous compter parmi les participants de cette édition exceptionnelle qui réunira les jeunes leaders du Sahel.</p>
            </div>

            <div class="participant-info">
                <h3 style="margin-top: 0; color: #1e3c72;">Vos Informations de Participation</h3>
                <div class="info-row">
                    <span class="info-label">Nom complet:</span>
                    <span class="info-value">{{ $participant['first_name'] }} {{ $participant['last_name'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $participant['email'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $participant['phone'] ?? 'Non renseigné' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pays:</span>
                    <span class="info-value">{{ $participant['country'] ?? 'Non renseigné' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ville:</span>
                    <span class="info-value">{{ $participant['city'] ?? 'Non renseigné' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Type d'inscription:</span>
                    <span class="info-value">{{ ucfirst($participant['registration_type'] ?? 'standard') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut:</span>
                    <span class="info-value" style="color: #28a745; font-weight: bold;">✅ Confirmé</span>
                </div>
            </div>

            <div class="event-details">
                <div class="event-title">Forum YouthConnekt Sahel 2025</div>
                <div class="event-date">📅 Dates: {{ $invitationData['event_date'] ?? 'À confirmer' }}</div>
                <div class="event-location">📍 Lieu: {{ $invitationData['event_location'] ?? 'N\'Djamena, Tchad' }}</div>
            </div>

            <div class="cta-section">
                <p><strong>Prochaines étapes :</strong></p>
                <p>1. Confirmez votre participation en répondant à cet email</p>
                <p>2. Consultez le programme détaillé sur notre site web</p>
                <p>3. Préparez-vous pour des échanges enrichissants avec les leaders de demain</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="http://localhost:3000/program" class="cta-button">Consulter le Programme</a>
            </div>
        </div>

        <div class="footer">
            <div class="footer-logo">YouthConnekt Sahel 2025</div>
            <div class="footer-text">Organisé par YouthConnekt Tchad</div>
            <div class="footer-text">Partenaire Officiel: PNUD Tchad</div>
            <div class="footer-text">Sous le haut patronage du Président de la République du Tchad</div>
            <div class="contact-info">
                📧 contact@youthconnekt.td | 📞 +235 66 16 17 53<br>
                🌐 www.youthconnekt-sahel.td
            </div>
        </div>
    </div>
</body>
</html>


