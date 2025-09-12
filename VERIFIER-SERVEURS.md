# 🔍 Vérification des Serveurs

## ✅ **Serveurs à Vérifier**

### **1. Serveur Frontend Express (Port 3000)**
- **URL** : `http://localhost:3000`
- **Status** : ✅ Fonctionne (visible dans les logs)

### **2. Serveur Backend Laravel (Port 8000)**
- **URL** : `http://localhost:8000`
- **API** : `http://localhost:8000/api/participants`
- **Status** : ❓ À vérifier

## 🧪 **Test Simple**

### **Étape 1 : Vérifier le Frontend**
1. Ouvrez : `http://localhost:3000`
2. Vous devriez voir la page d'accueil

### **Étape 2 : Vérifier le Backend**
1. Ouvrez : `http://localhost:8000`
2. Vous devriez voir la page Laravel par défaut

### **Étape 3 : Vérifier l'API**
1. Ouvrez : `http://localhost:8000/api/participants`
2. Vous devriez voir du JSON avec les participants

## 🚀 **Test du Dashboard**

### **Étape 1 : Accéder au Dashboard**
1. Ouvrez : `http://localhost:3000/admin/participants`
2. Connectez-vous (admin / admin123)

### **Étape 2 : Tester "Voir les Détails"**
1. Trouvez "FileTest User" dans la liste
2. Cliquez sur "Voir les détails"
3. Vérifiez les images dans le modal

## 🔍 **Diagnostic**

### **Si le backend ne répond pas :**
- Le serveur Laravel n'est pas démarré
- Port 8000 occupé par autre chose

### **Si l'API ne retourne pas de données :**
- Problème de base de données
- Problème de configuration Laravel

### **Si le frontend ne peut pas accéder à l'API :**
- Problème de communication entre serveurs
- Problème de CORS

## 📋 **Instructions**

**Dites-moi :**
1. **Le frontend fonctionne-t-il ?** (`http://localhost:3000`)
2. **Le backend fonctionne-t-il ?** (`http://localhost:8000`)
3. **L'API retourne-t-elle des données ?** (`http://localhost:8000/api/participants`)
4. **Le dashboard s'ouvre-t-il ?** (`http://localhost:3000/admin/participants`)

**Avec ces informations, je pourrai identifier le problème exact !**
