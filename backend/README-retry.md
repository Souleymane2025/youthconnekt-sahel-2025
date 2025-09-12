Retry automatique des inscriptions pendantes

But : exécuter périodiquement `scripts/retry_pending_direct.php` pour renvoyer les inscriptions qui étaient en file.

1) Script prêt : `backend/scripts/run_retry_task.ps1` — exécute le retry et écrit un log horodaté dans `storage/logs/retries`.

2) Pour créer une tâche Windows (toutes les 5 minutes) :
   - Ouvrir PowerShell en administrateur et exécuter :

     schtasks /Create /SC MINUTE /MO 5 /TN "YC-Retry-Pending" /TR "powershell -NoProfile -ExecutionPolicy Bypass -File \"C:\\Users\\Hp\\Desktop\\youthconnekt-sahel-2025\\backend\\scripts\\run_retry_task.ps1\"" /F

   - Pour tester manuellement :

     powershell -NoProfile -ExecutionPolicy Bypass -File "C:\Users\Hp\Desktop\youthconnekt-sahel-2025\backend\scripts\run_retry_task.ps1"

3) Logs : stockés dans `backend/storage/logs/retries` (fichiers `retry-YYYY-MM-DD_HH-mm-ss.log`).

4) Notifications
   - Le script `run_retry_task.ps1` appelle `notify_failure.ps1` si le retry PHP rapporte des échecs (Failed > 0).
   - Configuration (optionnelle) via variables d'environnement:
       - NOTIFY_SMTP_HOST, NOTIFY_SMTP_PORT, NOTIFY_SMTP_USER, NOTIFY_SMTP_PASS, NOTIFY_TO
   - Si SMTP non configuré, une alerte est écrite dans `backend/storage/logs/alerts/alert-YYYY-MM-DD_HH-mm-ss.txt`.

   - SMS via Twilio (optionnel):
     - Set the following env vars for SMS notifications:
       - TWILIO_ACCOUNT_SID - your Twilio Account SID
       - TWILIO_AUTH_TOKEN  - your Twilio Auth Token
       - TWILIO_FROM        - the Twilio phone number (in E.164, e.g. +1234567890)
       - NOTIFY_SMS_TO      - destination phone number (E.164)
     - When these are present the `notify_failure.ps1` script will POST to Twilio's API and attempt to send an SMS. Email notifications still run if SMTP is configured; SMS is an additional channel.

   - Example (PowerShell) to set env vars for the current session before running the task manually:

   $env:TWILIO_ACCOUNT_SID = 'ACxxxx'
   $env:TWILIO_AUTH_TOKEN = 'your_token'
   $env:TWILIO_FROM = '+15551234567'
   $env:NOTIFY_SMS_TO = '+228XXXXXXXX'


Conseil : commencer par /MO 5 (toutes les 5 minutes) puis augmenter l'intervalle selon charge. Assurez-vous que PHP CLI est dans le PATH pour l'utilisateur qui exécute la tâche.  

Si vous souhaitez, je peux aussi créer la tâche automatiquement depuis ici (je peux lancer la commande schtasks pour vous).
