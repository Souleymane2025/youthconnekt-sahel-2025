# Spécifications des Images pour YouthConnekt Sahel 2025

## 📸 Guide d'Insertion des Images Réelles

Ce document vous guide pour insérer les images réelles dans le site refondé de YouthConnekt Sahel 2025.

---

## 🎯 Images Hero Section

### Image Principale du Hero
- **Chemin**: `/images/hero/youthconnekt-sahel-2025-main.jpg`
- **Dimensions recommandées**: 1920x1080px (16:9)
- **Format**: JPG, PNG ou WebP
- **Contenu**: Vue d'ensemble du forum avec participants, scène, ambiance générale
- **Fallback**: `/images/hero/youth-sahel-main.jpg`

### Image Principale du Forum
- **Chemin**: `/images/hero/forum-youthconnekt-main.jpg`
- **Dimensions recommandées**: 1200x800px (3:2)
- **Format**: JPG, PNG ou WebP
- **Contenu**: Image principale du forum avec participants et scène
- **Fallback**: `/images/hero/youth-sahel-main.jpg`

### Galerie Secondaire Hero
- **Chemin**: `/images/gallery/jeunes-entrepreneurs-sahel.jpg`
- **Dimensions**: 300x200px (3:2)
- **Contenu**: Jeunes entrepreneurs du Sahel en action

- **Chemin**: `/images/gallery/innovation-technologique-sahel.jpg`
- **Dimensions**: 300x200px (3:2)
- **Contenu**: Innovation technologique au Sahel

- **Chemin**: `/images/gallery/leadership-feminin-sahel.jpg`
- **Dimensions**: 300x200px (3:2)
- **Contenu**: Leadership féminin au Sahel

---

## 🏛️ Images Forum Section

### Image Principale du Forum
- **Chemin**: `/images/forum/forum-youthconnekt-sahel-2025.jpg`
- **Dimensions recommandées**: 1200x800px (3:2)
- **Format**: JPG, PNG ou WebP
- **Contenu**: Vue d'ensemble du Forum YouthConnekt Sahel 2025
- **Fallback**: `/images/hero/forum-event.jpg`

### Galerie Forum
- **Chemin**: `/images/forum/participants-forum-sahel.jpg`
- **Dimensions**: 300x200px (3:2)
- **Contenu**: Participants du forum en action

- **Chemin**: `/images/forum/conference-salle-sahel.jpg`
- **Dimensions**: 300x200px (3:2)
- **Contenu**: Salle de conférence avec participants

- **Chemin**: `/images/forum/networking-jeunes-sahel.jpg`
- **Dimensions**: 300x200px (3:2)
- **Contenu**: Networking entre jeunes

---

## 🖼️ Galerie d'Images Réelles

### Images Entrepreneuriat
- **Chemin**: `/images/gallery/entrepreneurs-jeunes-sahel.jpg`
- **Dimensions recommandées**: 600x400px (3:2)
- **Contenu**: Jeunes entrepreneurs du Sahel innovants

### Images Innovation
- **Chemin**: `/images/gallery/innovation-tech-sahel.jpg`
- **Dimensions recommandées**: 600x400px (3:2)
- **Contenu**: Technologies émergentes au Sahel

### Images Leadership
- **Chemin**: `/images/gallery/leadership-femmes-sahel.jpg`
- **Dimensions recommandées**: 600x400px (3:2)
- **Contenu**: Femmes leaders inspirantes du Sahel

### Images Formation
- **Chemin**: `/images/gallery/ateliers-formation-sahel.jpg`
- **Dimensions recommandées**: 600x400px (3:2)
- **Contenu**: Ateliers de formation pratiques

### Images Networking
- **Chemin**: `/images/gallery/networking-jeunes-afrique.jpg`
- **Dimensions recommandées**: 600x400px (3:2)
- **Contenu**: Networking entre jeunes africains

### Images Culture
- **Chemin**: `/images/gallery/culture-traditions-sahel.jpg`
- **Dimensions recommandées**: 600x400px (3:2)
- **Contenu**: Culture et traditions du Sahel

---

## 🏢 Logos Partenaires

### Ministère de la Jeunesse et des Sports du Tchad
- **Chemin**: `/images/partners/ministere-jeunesse-sports-tchad.png`
- **Dimensions recommandées**: 200x100px (2:1)
- **Format**: PNG avec transparence
- **Fallback**: `/images/partners/ministere-jeunesse.png`

### PNUD Tchad
- **Chemin**: `/images/partners/pnud-tchad-official.png`
- **Dimensions recommandées**: 200x100px (2:1)
- **Format**: PNG avec transparence
- **Fallback**: `/images/partners/pnud.png`

### YouthConnekt Africa
- **Chemin**: `/images/partners/youthconnekt-africa-official.png`
- **Dimensions recommandées**: 200x100px (2:1)
- **Format**: PNG avec transparence
- **Fallback**: `/images/logos/youthconnekt-official-logo.png`

### UNICEF
- **Chemin**: `/images/partners/unicef-official.png`
- **Dimensions recommandées**: 200x100px (2:1)
- **Format**: PNG avec transparence
- **Fallback**: `/images/partners/unicef.png`

### Région du Sahel
- **Chemin**: `/images/partners/sahel-region-official.png`
- **Dimensions recommandées**: 200x100px (2:1)
- **Format**: PNG avec transparence
- **Fallback**: `/images/partners/sahel.png`

### République du Tchad
- **Chemin**: `/images/partners/republique-tchad-official.png`
- **Dimensions recommandées**: 200x100px (2:1)
- **Format**: PNG avec transparence
- **Fallback**: `/images/partners/republique-tchad.png`

---

## 📋 Instructions d'Insertion

### 1. Préparation des Images
- Optimisez les images pour le web (compression JPG à 85%, PNG avec transparence)
- Respectez les dimensions recommandées
- Utilisez des noms de fichiers descriptifs
- Vérifiez la qualité et la netteté

### 2. Structure des Dossiers
```
frontend/public/images/
├── hero/
│   ├── youthconnekt-sahel-2025-main.jpg
│   └── forum-youthconnekt-main.jpg
├── forum/
│   ├── forum-youthconnekt-sahel-2025.jpg
│   ├── participants-forum-sahel.jpg
│   ├── conference-salle-sahel.jpg
│   └── networking-jeunes-sahel.jpg
├── gallery/
│   ├── entrepreneurs-jeunes-sahel.jpg
│   ├── innovation-tech-sahel.jpg
│   ├── leadership-femmes-sahel.jpg
│   ├── ateliers-formation-sahel.jpg
│   ├── networking-jeunes-afrique.jpg
│   └── culture-traditions-sahel.jpg
└── partners/
    ├── ministere-jeunesse-sports-tchad.png
    ├── pnud-tchad-official.png
    ├── youthconnekt-africa-official.png
    ├── unicef-official.png
    ├── sahel-region-official.png
    └── republique-tchad-official.png
```

### 3. Fallbacks Automatiques
Le système inclut des fallbacks automatiques. Si une image n'est pas trouvée, le système utilisera automatiquement une image de remplacement.

### 4. Optimisation Web
- Utilisez des formats modernes (WebP) quand possible
- Compressez les images sans perte de qualité visible
- Créez des versions responsives si nécessaire

---

## 🎨 Conseils Visuels

### Style des Images
- **Couleurs**: Harmonisez avec la palette YouthConnekt (vert #2E7D32, orange #FF5722)
- **Luminosité**: Images lumineuses et optimistes
- **Composition**: Mise en avant des jeunes, de l'innovation, de la diversité
- **Qualité**: Images haute résolution et professionnelles

### Contenu Recommandé
- Jeunes entrepreneurs en action
- Moments de networking et collaboration
- Technologies et innovation
- Leadership féminin
- Diversité culturelle du Sahel
- Ambiance positive et dynamique

---

## ✅ Checklist de Validation

- [ ] Toutes les images sont optimisées pour le web
- [ ] Les dimensions respectent les spécifications
- [ ] Les formats sont corrects (JPG pour photos, PNG pour logos)
- [ ] Les noms de fichiers sont cohérents
- [ ] Les fallbacks fonctionnent
- [ ] Les images sont de qualité professionnelle
- [ ] Les droits d'utilisation sont respectés

---

*Ce document sera mis à jour selon les besoins du projet YouthConnekt Sahel 2025.*
