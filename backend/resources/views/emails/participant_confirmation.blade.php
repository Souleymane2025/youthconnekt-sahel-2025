<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Confirmation d'inscription</title>
  <style>
    body { font-family: Arial, sans-serif; color: #333; }
    .container { max-width:600px; margin:0 auto; padding:20px; border:1px solid #eee; }
    .header { background:#0b67a3; color:#fff; padding:12px; text-align:center }
    .content { padding:12px }
    .footer { font-size:12px; color:#777; padding:12px; text-align:center }
    .btn { display:inline-block; padding:10px 14px; background:#0b67a3; color:#fff; text-decoration:none; border-radius:4px }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>YouthConnekt Sahel 2025</h2>
    </div>
    <div class="content">
      <p>Bonjour {{ $participant->first_name }} {{ $participant->last_name }},</p>
      <p>Merci pour votre inscription à YouthConnekt Sahel 2025. Votre dossier est bien reçu et est en cours de traitement.</p>
      <p>Résumé de l'inscription :</p>
      <ul>
        <li><strong>Nom :</strong> {{ $participant->first_name }} {{ $participant->last_name }}</li>
        <li><strong>E-mail :</strong> {{ $participant->email }}</li>
        <li><strong>Pays :</strong> {{ $participant->country }}</li>
        <li><strong>Ville :</strong> {{ $participant->city }}</li>
      </ul>
      <p>Nous vous contacterons bientôt pour confirmer votre participation.</p>
      <p style="text-align:center"><a class="btn" href="{{ url('/') }}">Voir le site</a></p>
    </div>
    <div class="footer">
      © YouthConnekt Sahel 2025
    </div>
  </div>
</body>
</html>
