Recovery helper

Steps to run on the host (PowerShell) from the `backend` folder:

1) Ensure PHP CLI has PDO and sqlite drivers installed (php -m | Select-String 'pdo|sqlite')
2) Clear and rebuild Laravel caches:
   php artisan optimize:clear
   php artisan config:clear
   php artisan cache:clear
3) Link storage:
   php artisan storage:link
4) Run the direct retry script (bypasses Laravel):
   php -f scripts/retry_pending_direct.php
   # or use the helper
   .\run-retry-direct.ps1

If you see PDO errors, install/enable the PHP sqlite driver. After successful run, check:
- storage/logs/retry_direct.log
- storage/pending_participants.json (should have remaining failures only)
- database/database.sqlite (should have participant rows)

