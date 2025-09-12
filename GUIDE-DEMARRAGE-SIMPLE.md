# 🚀 Guide de Démarrage Simple - YouthConnekt Sahel 2025

## ❌ Problème Identifié
Les serveurs ne démarrent pas correctement avec les scripts complexes. Voici la solution simple.

## ✅ Solution Simple

### **Option 1: Script Batch (Recommandé)**
```bash
start-servers-simple.bat
```
Double-cliquez sur ce fichier ou exécutez-le dans PowerShell.

### **Option 2: Script PowerShell**
```powershell
.\start-servers-working.ps1
```

### **Option 3: Démarrage Manuel**

#### **Terminal 1 - Backend Laravel**
```bash
cd backend
php artisan serve --host=0.0.0.0 --port=8000
```

#### **Terminal 2 - Frontend Express**
```bash
cd frontend
node server.js
```

## 🧪 Test des Serveurs

Après avoir démarré les serveurs, testez avec :
```powershell
.\test-servers.ps1
```

## 🎯 URLs à Tester

1. **Accueil** : http://localhost:3000
2. **Login Admin** : http://localhost:3000/admin/login
3. **Dashboard** : http://localhost:3000/admin/dashboard
4. **Participants** : http://localhost:3000/admin/participants

## 🔑 Identifiants
- **Nom d'utilisateur** : `admin`
- **Mot de passe** : `admin123`

## 🐛 Problèmes Courants

### **1. "Cannot find module server.js"**
- **Cause** : Vous êtes dans le mauvais dossier
- **Solution** : Utilisez `cd frontend` avant `node server.js`

### **2. "Could not open input file: artisan"**
- **Cause** : Vous êtes dans le mauvais dossier
- **Solution** : Utilisez `cd backend` avant `php artisan serve`

### **3. "The token '&&' is not a valid statement separator"**
- **Cause** : PowerShell ne supporte pas `&&`
- **Solution** : Utilisez les scripts fournis ou démarrez manuellement

### **4. Serveurs qui ne répondent pas**
- **Cause** : Les serveurs n'ont pas fini de démarrer
- **Solution** : Attendez 10-15 secondes et testez avec `test-servers.ps1`

## 🎉 Résultat Attendu

Quand tout fonctionne, vous devriez voir :
- ✅ Backend Laravel sur le port 8000
- ✅ Frontend Express sur le port 3000
- ✅ Dashboard accessible avec les identifiants admin/admin123
- ✅ Page des participants fonctionnelle

## 📞 Support

Si les problèmes persistent :
1. Vérifiez que vous êtes dans le bon dossier
2. Utilisez les scripts simples fournis
3. Attendez que les serveurs démarrent complètement
4. Testez avec le script de test

**Le système fonctionne parfaitement quand démarré correctement !** 🚀

