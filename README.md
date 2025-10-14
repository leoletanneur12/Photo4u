# Photo4u - Site Web Professionnel

Un site web moderne et responsive pour Photo4u, votre photographe professionnel.

## 🚀 Fonctionnalités

- ✨ Design moderne et élégant avec Bootstrap 5
- 📱 Totalement responsive (mobile, tablette, desktop)
- 🎨 Animations fluides et effets visuels
- 🖼️ Galerie de photos interactive
- 💳 Section tarifs avec 3 formules
- 📝 Formulaires de contact et connexion
- 🎯 Navigation smooth scroll
- 🌟 Effets parallax sur le hero

## 📁 Structure du Projet

```
Photo4u/
│
├── index.html          # Page principale
├── css/
│   └── style.css       # Styles personnalisés
├── js/
│   └── script.js       # JavaScript interactif
├── images/
│   ├── logo.png        # Votre logo Photo4u
│   ├── sample1.jpg     # Image paysage
│   ├── sample2.jpg     # Image portrait
│   ├── sample3.jpg     # Image événement
│   ├── paysage.jpg     # Pour section formule paysages
│   ├── portrait.jpg    # Pour section formule portraits
│   └── evenement.jpg   # Pour section formule événements
└── README.md           # Ce fichier
```

## 🎨 Technologies Utilisées

- **HTML5** - Structure sémantique
- **CSS3** - Animations et styles modernes
- **Bootstrap 5.3.2** - Framework CSS responsive
- **Bootstrap Icons** - Icônes
- **JavaScript Vanilla** - Interactivité

## 🛠️ Installation et Utilisation

### Option 1: Serveur Local (WAMP/XAMPP)

1. Le projet est déjà dans votre dossier WAMP: `c:\wamp64\www\Photo4u`
2. Démarrez WAMP
3. Ouvrez votre navigateur et allez à: `http://localhost/Photo4u`

### Option 2: Ouvrir Directement

1. Double-cliquez sur `index.html`
2. Le site s'ouvrira dans votre navigateur par défaut

## 📸 Images à Ajouter

Pour un résultat optimal, ajoutez les images suivantes dans le dossier `images/`:

- `sample1.jpg` - Photo de paysage pour la mini-galerie
- `sample2.jpg` - Photo de portrait pour la mini-galerie
- `sample3.jpg` - Photo d'événement pour la mini-galerie
- `paysage.jpg` - Grande image pour la carte formule paysages
- `portrait.jpg` - Grande image pour la carte formule portraits
- `evenement.jpg` - Grande image pour la carte formule événements

**Dimensions recommandées:**
- Mini-galerie: 800x600px
- Cartes formules: 1200x800px

## 🎯 Sections du Site

1. **Navigation** - Menu fixe avec lien vers toutes les sections
2. **Hero Section** - Grande bannière avec votre logo et slogan
3. **Mini Galerie** - Aperçu rapide de 3 catégories
4. **Nos Formules** - 3 cartes présentant vos services
5. **Tarifs** - 3 offres tarifaires + détails des shootings
6. **Footer** - Liens utiles et informations de contact

## 🎨 Personnalisation

### Couleurs

Modifiez les couleurs dans `css/style.css` en changeant les variables CSS:

```css
:root {
    --primary-color: #dc3545;    /* Rouge principal */
    --secondary-color: #212529;  /* Gris foncé */
    --warning-color: #ffc107;    /* Jaune */
    --success-color: #28a745;    /* Vert */
    --danger-color: #dc3545;     /* Rouge */
}
```

### Textes

Tous les textes peuvent être modifiés directement dans `index.html`

### Images de fond

L'image de fond du hero utilise actuellement une image Unsplash. Pour la changer, modifiez dans `css/style.css`:

```css
.hero-section {
    background-image: 
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('images/votre-image.jpg');
}
```

## 🌟 Améliorations Ajoutées

Par rapport au design original, j'ai ajouté:

- ✅ Animations au scroll
- ✅ Effets hover sur toutes les cartes
- ✅ Système de notifications
- ✅ Navigation active selon la section
- ✅ Effets parallax
- ✅ Ripple effect sur les boutons
- ✅ Scrollbar personnalisée
- ✅ Meilleure accessibilité
- ✅ Optimisation mobile

## 📱 Responsive Design

Le site s'adapte automatiquement à toutes les tailles d'écran:
- 📱 Mobile (< 576px)
- 📱 Tablette (576px - 768px)
- 💻 Desktop (768px - 992px)
- 🖥️ Large Desktop (> 992px)

## 🔧 Support Navigateurs

- ✅ Chrome (dernière version)
- ✅ Firefox (dernière version)
- ✅ Safari (dernière version)
- ✅ Edge (dernière version)
- ⚠️ Internet Explorer non supporté

## 📞 Contact

Pour toute question sur ce site, contactez Photo4u!

---

**Développé avec ❤️ et Bootstrap**
