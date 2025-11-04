# Photo4u - Site de Photographie Professionnel

Site web complet de photographie avec système d'authentification multi-rôles, gestion de crédits et marketplace de photos.

## 🚀 Fonctionnalités

### Système d'authentification
- **4 niveaux d'accès** : Visiteur, Client, Photographe, Admin
- **Sessions PHP** sécurisées avec bcrypt
- Inscription avec choix du rôle (Client ou Photographe)
- Connexion et déconnexion

### � Système de Crédits (Nouveau!)
- Recharge de crédits entre 5€ et 500€
- Paiement sécurisé des photos avec crédits
- Historique des transactions
- Solde affiché en temps réel

### Par rôle

#### 👁️ Visiteur (non connecté)
- Aperçu flou des photos (effet blur)
- Accès à la page d'accueil et tarifs
- Formulaire de contact
- Peut s'inscrire comme Client ou Photographe

#### 👤 Client
- **Système de crédits rechargeable** (5€ - 500€)
- Parcourir toutes les photos par catégorie
- Acheter des photos avec ses crédits
- Télécharger les photos achetées
- Dashboard personnel avec :
  - Solde de crédits
  - Historique des achats
  - Total dépensé
  - Bouton de recharge de crédits

#### 📷 Photographe
- **Accès UNIQUEMENT à ses propres photos** (pas d'achat possible)
- Upload et gestion de ses photos
- Création de catégories personnalisées avec icônes
- Définition des prix par photo
- Statistiques :
  - Nombre de photos publiées
  - Nombre de ventes
  - Revenus totaux
- ⚠️ **Pour acheter des photos d'autres photographes, doit créer un compte Client**

#### 🔧 Administrateur
- Vue complète du système
- Gestion des utilisateurs
- Gestion de toutes les photos
- Statistiques globales (utilisateurs, photos, revenus)
- Vue sur tous les achats

## 📁 Structure du Projet

```
Photo4u/
│
├── index.php                    # Page principale avec aperçu
├── login.php                    # Connexion
├── register.php                 # Inscription (Client/Photographe)

- Statistiques (utilisateurs, photos, revenus)

- Vue sur tous les achats## 🎨 Technologies Utilisées



├── logout.php                   # Déconnexion (redirige vers index.php)
├── contact.php                  # Formulaire de contact
├── config.php                   # Configuration base de données
├── session.php                  # Gestion des sessions et authentification
├── admin_dashboard.php          # Dashboard administrateur
├── photographe_dashboard.php    # Dashboard photographe (photos perso uniquement)
├── client_dashboard.php         # Dashboard client (avec système de crédits)
├── database.sql                 # Structure de la base de données
├── add_credits_system.sql       # Migration pour le système de crédits
├── update_database.sql          # Mises à jour de la BDD
├── css/
│   └── style.css               # Styles personnalisés
├── js/
│   └── script.js               # Interactions JavaScript
├── images/                      # Dossier des photos uploadées
│   ├── logo-photo4u.svg
│   └── ... (photos des photographes)
└── README.md                    # Ce fichier
```

## 📋 Prérequis

- **WAMP/XAMPP/MAMP** avec PHP 8.0+ et MySQL 5.7+
- **Apache** avec mod_rewrite activé
- **phpMyAdmin** (recommandé) ou accès MySQL en ligne de commande
- Navigateur web moderne

## 🔧 Installation

### 1. Cloner ou Télécharger

```bash
# Le projet est dans : c:\wamp64\www\Photo4u
```

### 2. Créer la base de données

**Via phpMyAdmin (Recommandé) :**
1. Ouvrez phpMyAdmin : `http://localhost/phpmyadmin`
2. Créez une nouvelle base de données : **`photo4u`**
3. Importez le fichier `database.sql`
4. Importez le fichier `add_credits_system.sql` (pour le système de crédits)

**Via ligne de commande MySQL :**

```bash

# Ouvrir phpMyAdmin (http://localhost/phpmyadmin)1. Double-cliquez sur `index.html`

# Créer une nouvelle base de données nommée "photo4u"2. Le site s'ouvrira dans votre navigateur par défaut

# Importer le fichier database.sql

```## 📸 Images à Ajouter



Ou via ligne de commande MySQL :Pour un résultat optimal, ajoutez les images suivantes dans le dossier `images/`:

```bash

mysql -u root -p < database.sql- `sample1.jpg` - Photo de paysage pour la mini-galerie

```- `sample2.jpg` - Photo de portrait pour la mini-galerie

- `sample3.jpg` - Photo d'événement pour la mini-galerie

### 3. Configuration- `paysage.jpg` - Grande image pour la carte formule paysages

Vérifier les paramètres dans `config.php` :- `portrait.jpg` - Grande image pour la carte formule portraits

```php- `evenement.jpg` - Grande image pour la carte formule événements

define('DB_HOST', 'localhost');

define('DB_NAME', 'photo4u');**Dimensions recommandées:**

define('DB_USER', 'root');- Mini-galerie: 800x600px

define('DB_PASS', ''); // Mot de passe MySQL si nécessaire- Cartes formules: 1200x800px

```

## 🎯 Sections du Site

### 4. Permissions

Assurez-vous que le dossier `images/` est accessible en écriture :1. **Navigation** - Menu fixe avec lien vers toutes les sections

```bash2. **Hero Section** - Grande bannière avec votre logo et slogan

# Windows (via PowerShell en tant qu'admin)3. **Mini Galerie** - Aperçu rapide de 3 catégories

icacls "C:\wamp64\www\Photo4u\images" /grant Users:F4. **Nos Formules** - 3 cartes présentant vos services

```5. **Tarifs** - 3 offres tarifaires + détails des shootings

6. **Footer** - Liens utiles et informations de contact

### 5. Lancer WAMP

- Démarrer tous les services (Apache + MySQL)## 🎨 Personnalisation

- Accéder au site : `http://localhost/Photo4u/`

### Couleurs

## 👥 Comptes de test

Modifiez les couleurs dans `css/style.css` en changeant les variables CSS:

### Admin

- **Identifiant** : `admin````css

- **Mot de passe** : `admin`:root {

- Accès complet au dashboard administrateur    --primary-color: #dc3545;    /* Rouge principal */

    --secondary-color: #212529;  /* Gris foncé */

### Photographe    --warning-color: #ffc107;    /* Jaune */

- **Identifiant** : `photo`    --success-color: #28a745;    /* Vert */

- **Mot de passe** : `photo`    --danger-color: #dc3545;     /* Rouge */

- Peut ajouter et gérer des photos}

```

### Client

- **Identifiant** : `leo`### Textes

- **Mot de passe** : `letanneur`

- Peut acheter et télécharger des photosTous les textes peuvent être modifiés directement dans `index.html`



## 📁 Structure du projet### Images de fond



```L'image de fond du hero utilise actuellement une image Unsplash. Pour la changer, modifiez dans `css/style.css`:

Photo4u/

├── index.php                    # Page d'accueil (accès restreint)```css

├── login.php                    # Page de connexion.hero-section {

├── register.php                 # Page d'inscription    background-image: 

├── logout.php                   # Script de déconnexion        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),

├── config.php                   # Configuration BDD        url('images/votre-image.jpg');

├── session.php                  # Gestion des sessions}

├── admin_dashboard.php          # Dashboard administrateur```

├── photographe_dashboard.php    # Dashboard photographe

├── client_dashboard.php         # Dashboard client## 🌟 Améliorations Ajoutées

├── database.sql                 # Structure de la base de données

├── css/Par rapport au design original, j'ai ajouté:

│   └── style.css               # Styles personnalisés

├── js/- ✅ Animations au scroll

│   └── script.js               # Interactions JavaScript- ✅ Effets hover sur toutes les cartes

├── images/                      # Dossier des images uploadées- ✅ Système de notifications

│   ├── logo-photo4u.svg- ✅ Navigation active selon la section

│   └── ... (photos)- ✅ Effets parallax

└── README.md- ✅ Ripple effect sur les boutons

```- ✅ Scrollbar personnalisée

- ✅ Meilleure accessibilité

## 🗄️ Base de données- ✅ Optimisation mobile



### Tables## 📱 Responsive Design



#### `users`Le site s'adapte automatiquement à toutes les tailles d'écran:

- Gestion des utilisateurs- 📱 Mobile (< 576px)

- Rôles : admin, photographe, client- 📱 Tablette (576px - 768px)

- Mots de passe hashés avec `password_hash()`- 💻 Desktop (768px - 992px)

- 🖥️ Large Desktop (> 992px)

#### `photos`

- Stockage des photos uploadées## 🔧 Support Navigateurs

- Lien avec le photographe

- Catégories : paysage, portrait, evenement- ✅ Chrome (dernière version)

- Prix personnalisables- ✅ Firefox (dernière version)

- ✅ Safari (dernière version)

#### `purchases`- ✅ Edge (dernière version)

- Historique des achats- ⚠️ Internet Explorer non supporté

- Lien client-photo

- Prix et date d'achat## 📞 Contact



## 🎨 Technologies utiliséesPour toute question sur ce site, contactez Photo4u!



- **Frontend** : HTML5, CSS3, Bootstrap 5.3.2, Bootstrap Icons---

- **Backend** : PHP 7.4+, PDO MySQL

- **Base de données** : MySQL/MariaDB**Développé avec ❤️ et Bootstrap**

- **JavaScript** : Vanilla JS (smooth scroll, animations, modals)

## 🔒 Sécurité

- Mots de passe hashés avec `password_hash()` (bcrypt)
- Requêtes préparées (PDO) contre les injections SQL
- Sessions PHP sécurisées
- Contrôles d'accès basés sur les rôles
- Validation des uploads de fichiers

## 🌐 Fonctionnement

### Flux visiteur
1. Arrive sur `index.php` → accès limité
2. Clique sur "S'inscrire" → `register.php`
3. Crée un compte client
4. Se connecte → `login.php`
5. Redirigé vers `client_dashboard.php`

### Flux photographe
1. Se connecte avec compte photographe
2. Accède à `photographe_dashboard.php`
3. Upload de nouvelles photos (titre, description, catégorie, prix)
4. Gère son portfolio

### Flux client
1. Se connecte avec compte client
2. Accède à la galerie complète sur `index.php`
3. Visite `client_dashboard.php`
4. Achète des photos
5. Télécharge ses achats

### Flux admin
1. Se connecte avec compte admin
2. Accède à `admin_dashboard.php`
3. Vue sur statistiques globales
4. Gestion utilisateurs et photos
5. Accès à tous les dashboards

## 📝 Notes de développement

### Mots de passe des comptes par défaut
Les mots de passe dans `database.sql` sont hashés. Pour créer un nouveau hash :
```php
echo password_hash('votre_mot_de_passe', PASSWORD_DEFAULT);
```

### Ajouter un nouveau rôle
1. Modifier l'ENUM dans la table `users`
2. Ajouter les fonctions de vérification dans `session.php`
3. Créer le dashboard correspondant

### Catégories de photos
Les catégories actuelles : paysage, portrait, evenement
Pour en ajouter, modifier l'ENUM dans la table `photos`.

## 🐛 Dépannage

### Erreur de connexion à la base de données
- Vérifier que MySQL est démarré dans WAMP
- Vérifier les credentials dans `config.php`
- S'assurer que la BDD `photo4u` existe

### Images ne s'affichent pas
- Vérifier les permissions du dossier `images/`
- S'assurer que le chemin est correct
- Vérifier que les fichiers existent

### Problème de session
- Vérifier que `session.php` est bien inclus
- Vider le cache du navigateur
- Vérifier la configuration PHP de session

## 📞 Support

Pour toute question ou problème :
- Consulter les logs d'erreur PHP
- Vérifier les logs MySQL
- Inspecter la console du navigateur

## 📜 Licence

Projet éducatif - Photo4u © 2025

---

**Développé avec Bootstrap 5, PHP et MySQL**
