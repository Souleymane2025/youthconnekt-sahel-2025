# Guide de Gestion des Participants - YouthConnekt Sahel 2025

## 📋 Vue d'ensemble

Ce guide explique comment utiliser les nouvelles fonctionnalités de gestion des participants, incluant la suppression des participants de test et l'envoi d'invitations officielles.

---

## 🗑️ Suppression des Participants de Test

### Méthode 1: Via le Dashboard Web

1. **Accéder au Dashboard**
   - Ouvrez votre navigateur
   - Allez sur `http://localhost:3000/admin/participants`

2. **Supprimer tous les participants**
   - Cliquez sur le bouton rouge "Vider la base"
   - Confirmez l'action (double confirmation requise)
   - Tous les participants seront supprimés

3. **Supprimer un participant individuel**
   - Trouvez le participant dans le tableau
   - Cliquez sur l'icône de poubelle (🗑️) dans la colonne Actions
   - Confirmez la suppression

### Méthode 2: Via les Scripts

#### Script Batch (Windows)
```bash
# Double-cliquez sur le fichier
GESTION-PARTICIPANTS.bat

# Ou exécutez dans le terminal
./GESTION-PARTICIPANTS.bat
```

#### Script PowerShell
```powershell
# Exécutez dans PowerShell
./GESTION-PARTICIPANTS.ps1
```

#### Script PHP Direct
```bash
# Supprimer tous les participants
cd backend
php scripts/clear_all_participants.php
```

---

## 📧 Envoi d'Invitations Officielles

### Méthode 1: Via le Dashboard Web

1. **Accéder au Dashboard**
   - Ouvrez `http://localhost:3000/admin/participants`

2. **Envoyer une invitation**
   - Trouvez le participant avec le statut "En attente"
   - Cliquez sur l'icône d'enveloppe (📧) dans la colonne Actions
   - L'invitation sera envoyée automatiquement

3. **Envoyer un badge**
   - Pour les participants avec statut "Invité" ou "Confirmé"
   - Cliquez sur l'icône de badge (🆔) dans la colonne Actions

### Méthode 2: Via les Scripts

#### Script d'invitation individuelle
```bash
cd backend
php scripts/send_invitation.php [ID_PARTICIPANT]

# Exemple:
php scripts/send_invitation.php 1
```

---

## 🎯 Statuts des Participants

| Statut | Description | Actions Disponibles |
|--------|-------------|-------------------|
| **En attente** | Inscription en cours de validation | Envoyer invitation, Confirmer, Rejeter |
| **Invité** | Invitation envoyée | Envoyer badge, Confirmer |
| **Confirmé** | Participant confirmé | Envoyer badge |
| **Rejeté** | Inscription rejetée | Supprimer |
| **Badge envoyé** | Badge numérique envoyé | - |

---

## 🔧 API Endpoints

### Suppression
```http
DELETE /api/participants/{id}
POST /api/participants/clear-all
```

### Invitations
```http
POST /api/participants/{id}/send-invitation
POST /api/participants/{id}/send-badge
```

### Exemples d'utilisation

#### Supprimer un participant
```javascript
fetch('http://localhost:8000/api/participants/1', {
    method: 'DELETE'
})
.then(response => response.json())
.then(data => console.log(data));
```

#### Envoyer une invitation
```javascript
fetch('http://localhost:8000/api/participants/1/send-invitation', {
    method: 'POST'
})
.then(response => response.json())
.then(data => console.log(data));
```

#### Supprimer tous les participants
```javascript
fetch('http://localhost:8000/api/participants/clear-all', {
    method: 'POST'
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## 📊 Contenu des Emails d'Invitation

Les invitations officielles incluent:

- **En-tête**: Logo et titre YouthConnekt Sahel 2025
- **Détails personnalisés**: Nom, email, pays, ville, statut, organisation
- **Informations de l'événement**: Dates, lieu, thème
- **Prochaines étapes**: Confirmation, badge, programme, logistique
- **Pied de page**: Informations de contact et désinscription

### Template d'Email
```
🎉 YouthConnekt Sahel 2025 - Invitation Officielle

Cher(e) [Nom] [Prénom],

Nous avons le plaisir de vous confirmer votre inscription au Forum YouthConnekt Sahel 2025...

📋 Détails de votre inscription:
- Nom: [Nom complet]
- Email: [Email]
- Pays: [Pays]
- Ville: [Ville]
- Statut: [Occupation]
- Organisation: [Organisation]

🎯 Prochaines étapes:
1. Confirmation: Votre inscription a été validée
2. Badge: Vous recevrez votre badge numérique
3. Programme: Le programme détaillé sera disponible
4. Logistique: Les informations pratiques vous seront envoyées

📅 Informations importantes:
- Dates: 13-15 Octobre 2025
- Lieu: N'Djamena, Tchad
- Thème: Connectons, innovons et transformons ensemble l'avenir du Sahel
```

---

## ⚠️ Sécurité et Confirmation

### Double Confirmation
- Toutes les actions de suppression nécessitent une double confirmation
- Les invitations sont envoyées avec un log détaillé
- Les fichiers associés (photos, documents) sont supprimés avec le participant

### Logs et Traçabilité
- Tous les envois d'emails sont loggés
- Les changements de statut sont tracés
- Les suppressions sont irréversibles

---

## 🚀 Démarrage Rapide

### 1. Supprimer les participants de test
```bash
# Option A: Script automatique
./GESTION-PARTICIPANTS.bat

# Option B: Commande directe
cd backend && php scripts/clear_all_participants.php
```

### 2. Tester l'envoi d'invitation
```bash
# Créer un participant de test d'abord via le formulaire web
# Puis envoyer l'invitation
cd backend && php scripts/send_invitation.php 1
```

### 3. Vérifier via le dashboard
- Ouvrir `http://localhost:3000/admin/participants`
- Vérifier que les actions fonctionnent
- Tester l'envoi d'invitations

---

## 🔍 Dépannage

### Problèmes Courants

#### "API Backend non connectée"
- Vérifiez que le serveur Laravel est démarré sur le port 8000
- Testez: `http://localhost:8000/api/healthz`

#### "Participant non trouvé"
- Vérifiez l'ID du participant dans la base de données
- Utilisez le script `read_participants.php` pour lister les participants

#### "Erreur lors de l'envoi"
- Vérifiez les logs Laravel
- Assurez-vous que la configuration email est correcte

### Commandes de Diagnostic
```bash
# Vérifier la base de données
cd backend && php scripts/read_participants.php

# Tester l'API
curl http://localhost:8000/api/healthz

# Vérifier les logs
tail -f backend/storage/logs/laravel.log
```

---

## 📞 Support

Pour toute question ou problème:
1. Vérifiez les logs dans `backend/storage/logs/`
2. Testez l'API directement: `http://localhost:8000/api/participants`
3. Utilisez les scripts de diagnostic fournis

---

*Ce guide est mis à jour régulièrement selon les évolutions du système YouthConnekt Sahel 2025.*
