# Spécifications des Images pour la Page Speakers - YouthConnekt Sahel 2025

## 📸 Guide d'Insertion des Images Réelles des Intervenants

Ce document vous guide pour insérer les images réelles des intervenants dans la page refondée des speakers.

---

## 🎯 Images Hero Section Speakers

### Image Principale du Hero Speakers
- **Chemin**: `/images/speakers/speakers-hero-youthconnekt-sahel.jpg`
- **Dimensions recommandées**: 1920x1080px (16:9)
- **Format**: JPG, PNG ou WebP
- **Contenu**: Vue d'ensemble des intervenants du forum, scène avec speakers, ambiance de conférence
- **Fallback**: Utilise les images existantes si non trouvée

---

## 👑 Images Panel de Haut Niveau (Présidents)

### Président du Tchad
- **Chemin**: `/images/speakers/president-tchad-official.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo officielle du Président de la République du Tchad
- **Fallback**: `/images/speakers/president-tchad.jpg`

### Président du Burkina Faso
- **Chemin**: `/images/speakers/president-burkina-official.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo officielle du Président du Burkina Faso
- **Fallback**: `/images/speakers/president-burkina.jpg`

### Président du Sénégal
- **Chemin**: `/images/speakers/president-senegal-official.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo officielle du Président de la République du Sénégal
- **Fallback**: `/images/speakers/president-senegal.jpg`

---

## 👔 Images Ministres et Leaders Gouvernementaux

### Mme. Amina Priscille Longoh
- **Chemin**: `/images/speakers/amina-priscille-longoh.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo professionnelle de la Ministre de la Femme, de la Protection de la Petite Enfance et de la Solidarité Nationale
- **Fallback**: `/images/speakers/ministere-femme-tchad.jpg`

### Dr. Mahamat Zene Cherif
- **Chemin**: `/images/speakers/mahamat-zene-cherif.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo professionnelle du Ministre de l'Économie et de la Planification du Développement
- **Fallback**: `/images/speakers/ministere-economie-tchad.jpg`

### Mme. Mariam Mahamat Nour
- **Chemin**: `/images/speakers/mariam-mahamat-nour.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo professionnelle de la Ministre de l'Enseignement Supérieur, de la Recherche et de l'Innovation
- **Fallback**: `/images/speakers/ministere-education-tchad.jpg`

---

## 🚀 Images Entrepreneurs et Innovateurs

### Sarah Oumarou - AgriTech Solutions Mali
- **Chemin**: `/images/speakers/sarah-oumarou.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo professionnelle de la fondatrice d'AgriTech Solutions Mali
- **Fallback**: `/images/speakers/entrepreneur-agritech-mali.jpg`

### Prof. Ahmed Djibrine - Recteur Université de N'Djamena
- **Chemin**: `/images/speakers/ahmed-djibrine.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo professionnelle du Recteur de l'Université de N'Djamena
- **Fallback**: `/images/speakers/recteur-universite-ndjamena.jpg`

### Ibrahim Mahamat - CEO TechHub Sahel
- **Chemin**: `/images/speakers/ibrahim-mahamat-techhub.jpg`
- **Dimensions recommandées**: 400x400px (1:1)
- **Format**: JPG, PNG
- **Contenu**: Photo professionnelle du CEO et fondateur de TechHub Sahel
- **Fallback**: `/images/speakers/entrepreneur-tech-tchad.jpg`

---

## 📋 Instructions d'Insertion

### 1. Préparation des Images
- **Qualité**: Images haute résolution et professionnelles
- **Format**: JPG pour les photos, PNG si transparence nécessaire
- **Compression**: Optimisez pour le web (85% qualité JPG)
- **Orientation**: Photos de face, éclairage professionnel
- **Style**: Cohérent dans le style et la qualité

### 2. Structure des Dossiers
```
frontend/public/images/speakers/
├── speakers-hero-youthconnekt-sahel.jpg
├── president-tchad-official.jpg
├── president-burkina-official.jpg
├── president-senegal-official.jpg
├── amina-priscille-longoh.jpg
├── mahamat-zene-cherif.jpg
├── mariam-mahamat-nour.jpg
├── sarah-oumarou.jpg
├── ahmed-djibrine.jpg
├── ibrahim-mahamat-techhub.jpg
└── [autres images de fallback existantes]
```

### 3. Conseils Visuels

#### Style des Photos
- **Éclairage**: Lumière naturelle ou studio professionnel
- **Arrière-plan**: Neutre (blanc, gris clair) ou contexte professionnel
- **Expression**: Sourire naturel et professionnel
- **Tenue**: Tenue professionnelle appropriée au rôle

#### Cohérence Visuelle
- **Cadrage**: Photos centrées, cadrage similaire
- **Couleurs**: Harmonisation avec la palette YouthConnekt
- **Qualité**: Toutes les images de même niveau de qualité
- **Format**: Respecter les dimensions recommandées

### 4. Fallbacks Automatiques
Le système inclut des fallbacks automatiques. Si une image n'est pas trouvée, le système utilisera automatiquement une image de remplacement existante.

---

## 🎨 Design des Cartes Speakers

### Structure des Cartes
- **Image**: 300px de hauteur, effet de zoom au survol
- **Overlay**: Gradient avec badge de catégorie
- **Informations**: Nom, titre, description, tags, actions
- **Animations**: Hover effects et transitions fluides

### Catégories et Badges
- **Présidents**: Badge doré avec drapeau
- **Ministres**: Badge vert avec icône ministère
- **Entrepreneurs**: Badge bleu avec icône innovation
- **Académiques**: Badge violet avec icône université
- **Tech Leaders**: Badge orange avec icône tech

### Tags par Spécialité
- **Leadership Féminin**: Rose/Magenta
- **Économie Verte**: Vert
- **Innovation Éducative**: Violet
- **Agriculture Tech**: Vert clair
- **Tech & Innovation**: Orange/Rouge

---

## ✅ Checklist de Validation

### Images Présidents
- [ ] Photos officielles haute qualité
- [ ] Tenue protocolaire appropriée
- [ ] Arrière-plan professionnel
- [ ] Dimensions 400x400px respectées

### Images Ministres
- [ ] Photos professionnelles récentes
- [ ] Tenue professionnelle
- [ ] Expression confiante et souriante
- [ ] Qualité d'image cohérente

### Images Entrepreneurs
- [ ] Photos dynamiques et inspirantes
- [ ] Contexte professionnel ou entrepreneurial
- [ ] Expression énergique et motivante
- [ ] Style moderne et innovant

### Technique
- [ ] Toutes les images optimisées pour le web
- [ ] Formats corrects (JPG pour photos)
- [ ] Noms de fichiers cohérents
- [ ] Fallbacks fonctionnels
- [ ] Responsive design testé

---

## 🔄 Mise à Jour Continue

### Ajout de Nouveaux Speakers
1. Ajouter l'image dans `/images/speakers/`
2. Respecter les dimensions recommandées
3. Suivre la nomenclature de nommage
4. Tester les fallbacks

### Maintenance
- Vérifier régulièrement la qualité des images
- Mettre à jour les informations des speakers
- Optimiser les performances d'affichage
- Tester sur différents appareils

---

*Ce document sera mis à jour selon les besoins du projet YouthConnekt Sahel 2025.*
