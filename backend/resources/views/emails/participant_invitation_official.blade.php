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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            background: white;
            padding: 40px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .invitation-text {
            font-size: 16px;
            margin-bottom: 30px;
            text-align: justify;
        }
        .event-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #1e3c72;
        }
        .event-details h3 {
            color: #1e3c72;
            margin-top: 0;
            font-size: 20px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        .detail-value {
            flex: 1;
            color: #333;
        }
        .participant-info {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border: 1px solid #bee5eb;
        }
        .participant-info h3 {
            color: #0c5460;
            margin-top: 0;
            font-size: 18px;
        }
        .cta-button {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button a {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        .signature-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        .signature {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .signature-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
            border: 3px solid #1e3c72;
        }
        .signature-text {
            flex: 1;
        }
        .signature-name {
            font-weight: bold;
            font-size: 18px;
            color: #1e3c72;
            margin-bottom: 5px;
        }
        .signature-title {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .signature-organization {
            color: #495057;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/youthconnekt-logo.png') }}" alt="YouthConnekt Sahel" class="logo" onerror="this.style.display='none'">
        <h1>FORUM YOUTHCONNEKT SAHEL 2025</h1>
        <p>Réunir • Innover • Transformer</p>
    </div>

    <div class="content">
        <div class="greeting">
            Cher(e) <strong>{{ $participant->first_name }} {{ $participant->last_name }}</strong>,
        </div>

        <div class="invitation-text">
            <p>Au nom du Comité d'Organisation du Forum YouthConnekt Sahel 2025, j'ai le grand plaisir de vous inviter officiellement à participer à cet événement exceptionnel qui se tiendra du <strong>15 au 17 mars 2025</strong> à <strong>N'Djamena, Tchad</strong>.</p>

            <p>Votre profil et votre engagement dans le développement de notre région nous ont particulièrement impressionnés. Votre participation enrichira considérablement les échanges et contribuera au succès de cette initiative majeure pour la jeunesse sahélienne.</p>

            <p>Ce forum réunira plus de <strong>1000 jeunes leaders</strong> de la région du Sahel pour échanger sur les défis et opportunités de développement, partager des innovations et créer des partenariats durables.</p>
        </div>

        <div class="event-details">
            <h3>📅 Détails de l'Événement</h3>
            <div class="detail-row">
                <div class="detail-label">📅 Dates :</div>
                <div class="detail-value">15 - 17 Mars 2025</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">📍 Lieu :</div>
                <div class="detail-value">Palais des Congrès, N'Djamena, Tchad</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">👥 Participants :</div>
                <div class="detail-value">1000+ jeunes leaders du Sahel</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">🎯 Thème :</div>
                <div class="detail-value">"Innovation et Développement Durable pour le Sahel"</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">💰 Frais :</div>
                <div class="detail-value">Participation gratuite (hébergement et repas inclus)</div>
            </div>
        </div>

        <div class="participant-info">
            <h3>👤 Vos Informations d'Inscription</h3>
            <div class="detail-row">
                <div class="detail-label">Nom complet :</div>
                <div class="detail-value">{{ $participant->first_name }} {{ $participant->last_name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email :</div>
                <div class="detail-value">{{ $participant->email }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Téléphone :</div>
                <div class="detail-value">{{ $participant->phone }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Pays :</div>
                <div class="detail-value">{{ $participant->country }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Type :</div>
                <div class="detail-value">{{ $participant->type === 'national' ? 'Participant National' : 'Participant International' }}</div>
            </div>
            @if($participant->organization)
            <div class="detail-row">
                <div class="detail-label">Organisation :</div>
                <div class="detail-value">{{ $participant->organization }}</div>
            </div>
            @endif
        </div>

        <div class="cta-button">
            <a href="{{ url('/confirm-participation/' . $participant->id) }}">✅ Confirmer ma Participation</a>
        </div>

        <div class="invitation-text">
            <p><strong>Prochaines étapes :</strong></p>
            <ul>
                <li>Confirmez votre participation en cliquant sur le bouton ci-dessus</li>
                <li>Vous recevrez un email de confirmation avec votre badge numérique</li>
                <li>Un guide du participant vous sera envoyé 2 semaines avant l'événement</li>
                <li>Votre badge d'accès sera disponible à l'entrée du forum</li>
            </ul>

            <p>Pour toute question, n'hésitez pas à nous contacter à <strong>contact@youthconnektsahel2025.org</strong> ou au <strong>+235 66 12 34 56</strong>.</p>
        </div>

        <div class="signature-section">
            <div class="signature">
                <img src="{{ asset('images/presidente-signature.png') }}" alt="Signature Présidente" class="signature-image" onerror="this.style.display='none'">
                <div class="signature-text">
                    <div class="signature-name">Dr. Fatima Mahamat Saleh</div>
                    <div class="signature-title">Présidente du Comité d'Organisation</div>
                    <div class="signature-organization">Forum YouthConnekt Sahel 2025</div>
                </div>
            </div>
            <p style="font-style: italic; color: #6c757d; margin-top: 20px;">
                "Ensemble, nous construirons un Sahel prospère et innovant pour les générations futures."
            </p>
        </div>
    </div>

    <div class="footer">
        <p><strong>Forum YouthConnekt Sahel 2025</strong></p>
        <p>Ministère de la Jeunesse et des Sports • République du Tchad</p>
        <p>📧 contact@youthconnektsahel2025.org • 📞 +235 66 12 34 56</p>
        <p>🌐 www.youthconnektsahel2025.org</p>
        <p style="margin-top: 15px; font-size: 12px; color: #adb5bd;">
            Cette invitation est personnelle et non transférable. Merci de ne pas la partager.
        </p>
    </div>
</body>
</html>

