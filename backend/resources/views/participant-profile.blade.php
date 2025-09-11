<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profil Participant - {{ $participant->participant_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header h2 {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 18px;
            font-weight: normal;
        }
        .participant-id {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
            padding-left: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #495057;
            padding: 8px 15px 8px 0;
            width: 30%;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #212529;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-confirmed {
            background-color: #28a745;
            color: white;
        }
        .status-rejected {
            background-color: #dc3545;
            color: white;
        }
        .interests {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .interest-tag {
            background-color: #e9ecef;
            color: #495057;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .qr-placeholder {
            width: 100px;
            height: 100px;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>YouthConnekt Sahel 2025</h1>
            <h2>Profil Participant</h2>
            <div class="participant-id">{{ $participant->participant_id }}</div>
        </div>

        <!-- Personal Information -->
        <div class="section">
            <div class="section-title">Informations Personnelles</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nom complet:</div>
                    <div class="info-value">{{ $participant->first_name }} {{ $participant->last_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $participant->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Téléphone:</div>
                    <div class="info-value">{{ $participant->phone }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">WhatsApp:</div>
                    <div class="info-value">{{ $participant->whatsapp }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date de naissance:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($participant->birth_date)->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Genre:</div>
                    <div class="info-value">{{ ucfirst($participant->gender) }}</div>
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="section">
            <div class="section-title">Localisation</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Pays:</div>
                    <div class="info-value">{{ $participant->country }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ville:</div>
                    <div class="info-value">{{ $participant->city }}</div>
                </div>
                @if($participant->province)
                <div class="info-row">
                    <div class="info-label">Province:</div>
                    <div class="info-value">{{ $participant->province }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Professional Information -->
        <div class="section">
            <div class="section-title">Informations Professionnelles</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Statut:</div>
                    <div class="info-value">{{ ucfirst($participant->occupation) }}</div>
                </div>
                @if($participant->organization)
                <div class="info-row">
                    <div class="info-label">Organisation:</div>
                    <div class="info-value">{{ $participant->organization }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Type d'inscription:</div>
                    <div class="info-value">{{ ucfirst($participant->registration_type) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Domaines d'intérêt:</div>
                    <div class="info-value">
                        <div class="interests">
                            @foreach(json_decode($participant->interests, true) as $interest)
                                <span class="interest-tag">{{ ucfirst($interest) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Experience and Motivation -->
        @if($participant->experience || $participant->motivation)
        <div class="section">
            <div class="section-title">Expérience et Motivation</div>
            @if($participant->experience)
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Expérience:</div>
                    <div class="info-value">{{ $participant->experience }}</div>
                </div>
            </div>
            @endif
            @if($participant->motivation)
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Motivation:</div>
                    <div class="info-value">{{ $participant->motivation }}</div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Handicap Information -->
        @if($participant->handicap === 'yes')
        <div class="section">
            <div class="section-title">Informations sur le Handicap</div>
            <div class="info-grid">
                @if($participant->handicap_type)
                <div class="info-row">
                    <div class="info-label">Type de handicap:</div>
                    <div class="info-value">{{ ucfirst($participant->handicap_type) }}</div>
                </div>
                @endif
                @if($participant->handicap_accommodation)
                <div class="info-row">
                    <div class="info-label">Besoins d'accommodation:</div>
                    <div class="info-value">{{ $participant->handicap_accommodation }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Status and Registration -->
        <div class="section">
            <div class="section-title">Statut d'Inscription</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Statut:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $participant->status }}">
                            {{ ucfirst($participant->status) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date d'inscription:</div>
                    <div class="info-value">{{ $participant->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Newsletter:</div>
                    <div class="info-value">{{ $participant->newsletter ? 'Oui' : 'Non' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Consentement photos:</div>
                    <div class="info-value">{{ $participant->photos_consent ? 'Oui' : 'Non' }}</div>
                </div>
            </div>
        </div>

        <!-- QR Code Placeholder -->
        <div class="qr-placeholder">
            QR Code<br>
            {{ $participant->participant_id }}
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>YouthConnekt Sahel 2025</strong> | 13-15 Octobre 2025 | N'Djamena, Tchad</p>
            <p>Ce document est généré automatiquement et contient les informations d'inscription du participant.</p>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>








