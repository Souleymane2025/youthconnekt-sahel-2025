# 🚀 Guide de mise en ligne sur GitHub - YouthConnekt Sahel 2025

## 📋 Étapes complètes pour mettre le projet sur GitHub

### 1️⃣ **Configuration Git (à faire une seule fois)**

```bash
# Configurez votre identité Git
git config --global user.name "Votre Nom"
git config --global user.email "votre.email@example.com"

# Exemple :
git config --global user.name "YouthConnekt Sahel Team"
git config --global user.email "contact@youthconnekt-sahel-2025.com"
```

### 2️⃣ **Créer le repository sur GitHub**

1. **Allez sur GitHub.com** et connectez-vous
2. **Cliquez sur "New repository"** (bouton vert)
3. **Remplissez les informations :**
   - **Repository name** : `youthconnekt-sahel-2025`
   - **Description** : `Site officiel YouthConnekt Sahel 2025 - Forum de la jeunesse du Sahel`
   - **Visibilité** : Public (recommandé)
   - **Cochez** : "Add a README file"
   - **Cochez** : "Add .gitignore" → Choisir "Node"
4. **Cliquez** : "Create repository"

### 3️⃣ **Uploader le projet**

```bash
# Dans votre dossier du projet (PowerShell)
git init
git add .
git commit -m "Initial commit: YouthConnekt Sahel 2025 - Site officiel du Forum de la Jeunesse du Sahel"

# Ajouter le repository distant (remplacez VOTRE-USERNAME)
git remote add origin https://github.com/VOTRE-USERNAME/youthconnekt-sahel-2025.git

# Pousser vers GitHub
git branch -M main
git push -u origin main
```

### 4️⃣ **Alternative : Upload via interface GitHub**

Si vous préférez utiliser l'interface web :

1. **Créez le repository** sur GitHub (étape 2)
2. **Téléchargez votre projet** en ZIP
3. **Uploadez le ZIP** via l'interface GitHub :
   - Cliquez sur "uploading an existing file"
   - Glissez-déposez votre dossier
   - Ajoutez un message de commit
   - Cliquez "Commit changes"

### 5️⃣ **Vérification**

Votre repository devrait maintenant contenir :
- ✅ Tous les fichiers du projet
- ✅ README.md avec documentation
- ✅ .gitignore configuré
- ✅ Scripts de déploiement

## 🔗 URLs importantes après upload

- **Repository** : `https://github.com/VOTRE-USERNAME/youthconnekt-sahel-2025`
- **Clone** : `git clone https://github.com/VOTRE-USERNAME/youthconnekt-sahel-2025.git`

## 🚀 Déploiement depuis GitHub

Une fois sur GitHub, vous pourrez déployer sur votre VPS avec :

```bash
# Sur votre VPS Hostinger
cd /var/www
git clone https://github.com/VOTRE-USERNAME/youthconnekt-sahel-2025.git
cd youthconnekt-sahel-2025
./scripts/setup-server.sh
./scripts/deploy.sh production
```

## 📞 Support

Si vous rencontrez des problèmes :
1. **Vérifiez** que Git est installé : `git --version`
2. **Configurez** votre identité Git (étape 1)
3. **Vérifiez** votre connexion GitHub
4. **Utilisez** l'upload ZIP si nécessaire (étape 4)

