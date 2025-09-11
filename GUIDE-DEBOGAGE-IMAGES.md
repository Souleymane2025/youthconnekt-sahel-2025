# 🔍 Guide de Débogage - Images Non Visibles

## ✅ **État Actuel Confirmé**

Le test backend montre que :
- ✅ **Structure de table** : Tous les champs d'images existent
- ✅ **Données** : `FileTest User` a un passeport (`/storage/participants/passports/...`)
- ✅ **API** : Les champs d'images sont inclus dans la réponse
- ✅ **IDs** : Format `FYCS2025...` fonctionne

## 🔍 **Étapes de Débogage**

### **1. Vérifier les Serveurs**
```bash
# Terminal 1 - Backend Laravel
cd backend
php artisan serve

# Terminal 2 - Frontend Node.js  
npm start
```

**URLs à vérifier :**
- Backend : `http://localhost:8000/api/participants`
- Frontend : `http://localhost:3000/admin/participants`

### **2. Test de l'API Backend**
Ouvrez dans le navigateur : `http://localhost:8000/api/participants`

**Résultat attendu :**
```json
{
  "data": [
    {
      "id": 3,
      "participant_id": "FYCS20252509112940",
      "first_name": "FileTest",
      "last_name": "User",
      "photo_path": "",
      "passport_path": "/storage/participants/passports/...",
      "cin_path": "",
      ...
    }
  ]
}
```

### **3. Test du Frontend**
1. Ouvrez : `http://localhost:3000/admin/participants`
2. Ouvrez la **Console du navigateur** (F12)
3. Cliquez sur **"Voir les détails"** d'un participant
4. Vérifiez les logs dans la console

### **4. Vérifications dans la Console**

**Recherchez ces logs :**
```javascript
🔄 Chargement des participants...
📊 Réponse API: {success: true, data: [...]}
✅ X participants chargés
🔍 viewParticipant appelée avec ID: X
📊 Participants disponibles: [...]
👤 Participant trouvé: {...}
```

**Si vous voyez des erreurs :**
- ❌ `❌ Erreur API:` → Problème de connexion backend
- ❌ `❌ Participant non trouvé` → Problème de données
- ❌ `❌ Élément viewParticipantContent non trouvé` → Problème de DOM

### **5. Vérification des Images**

**Dans le modal "Voir les détails", vérifiez :**

1. **Section "Documents et Images"**
   - Photo de profil : Avatar par défaut ou image réelle
   - Passeport : Image réelle ou "Document non fourni"
   - Pièce d'identité : Image réelle ou "Document non fourni"

2. **ID du participant**
   - Format : `FYCS20252509112940` (pas juste un nombre)

### **6. Problèmes Possibles**

#### **A. Images ne s'affichent pas du tout**
- **Cause** : Problème de connexion API
- **Solution** : Vérifier que les deux serveurs fonctionnent

#### **B. Images montrent "Document non fourni"**
- **Cause** : Les champs d'images sont vides dans la base
- **Solution** : Normal pour les participants sans images

#### **C. Images montrent des erreurs (icône cassée)**
- **Cause** : Chemins d'images incorrects
- **Solution** : Vérifier les chemins dans la base de données

#### **D. Modal ne s'ouvre pas**
- **Cause** : Erreur JavaScript
- **Solution** : Vérifier la console pour les erreurs

### **7. Test Spécifique**

**Pour tester avec `FileTest User` (qui a un passeport) :**

1. Ouvrez le dashboard participants
2. Trouvez "FileTest User" dans la liste
3. Cliquez sur "Voir les détails"
4. Dans la section "Documents et Images"
5. Vérifiez que le passeport s'affiche

**Résultat attendu :**
- ✅ Photo de profil : Avatar par défaut
- ✅ Passeport : Image réelle visible
- ✅ Pièce d'identité : "Document non fourni"

### **8. Actions Correctives**

#### **Si l'API ne répond pas :**
```bash
cd backend
php artisan serve
```

#### **Si le frontend ne se charge pas :**
```bash
npm start
```

#### **Si les images ne s'affichent pas :**
1. Vérifiez la console du navigateur
2. Vérifiez que l'API retourne les bons champs
3. Vérifiez que les chemins d'images sont corrects

## 🎯 **Résultat Attendu**

Après ces vérifications, vous devriez voir :
- ✅ **ID spécifique** : `FYCS20252509112940`
- ✅ **Images** : Passeport visible pour FileTest User
- ✅ **Fallback** : Avatar par défaut pour images manquantes
- ✅ **Modal** : Design moderne et fonctionnel

**🔍 Suivez ces étapes et dites-moi ce que vous observez !**
