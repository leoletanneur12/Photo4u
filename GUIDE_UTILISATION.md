# 🎓 Guide d'utilisation - Photo4u

## 🚀 Démarrage rapide

### Étape 1 : Mise à jour de la base de données ⚠️

**IMPORTANT** : Avant d'utiliser les nouvelles fonctionnalités, vous DEVEZ mettre à jour la base de données.

1. Ouvrez phpMyAdmin : `http://localhost/phpmyadmin`
2. Sélectionnez la base de données `photo4u`
3. Cliquez sur l'onglet **SQL**
4. Ouvrez le fichier `update_database.sql` dans un éditeur de texte
5. Copiez tout le contenu
6. Collez-le dans la zone SQL de phpMyAdmin
7. Cliquez sur **Exécuter**

✅ Vous devriez voir : "Query OK" ou "X rows affected"

---

## 👨‍💼 Guide Photographe

### Se connecter en tant que photographe

1. Allez sur `http://localhost/Photo4u/login.php`
2. Utilisez les identifiants : **photo** / **photo**
3. Vous serez redirigé vers votre dashboard photographe

### 📊 Comprendre votre dashboard

Votre dashboard est divisé en **3 sections principales** :

#### 1. **Statistiques** (en haut)
- 🖼️ **Photos** : Nombre total de photos que vous avez publiées
- 🛒 **Ventes** : Combien de fois vos photos ont été achetées
- 💰 **Revenus** : Total des revenus générés

#### 2. **Onglets de navigation**
- **Mes Photos** : Voir toutes vos photos publiées
- **Catégories** : Gérer vos catégories personnalisées
- **Ajouter une photo** : Publier une nouvelle photo

---

### 🗂️ Créer vos catégories

**Pourquoi créer des catégories ?**
- Organiser votre portfolio
- Aider les clients à trouver vos photos
- Personnaliser l'apparence avec des icônes

**Comment faire ?**

1. Cliquez sur l'onglet **Catégories**
2. Dans le formulaire "Créer une catégorie" :
   - **Nom** : Ex: "Mariage", "Nature", "Architecture"
   - **Description** : Décrivez le type de photos (optionnel)
   - **Icône** : Code d'icône Bootstrap Icons (ex: `bi-heart`, `bi-tree`, `bi-building`)
3. Cliquez sur **Créer la catégorie**

💡 **Astuce** : Visitez https://icons.getbootstrap.com/ pour trouver des icônes

**Exemples de catégories** :
- 💒 Mariage → `bi-heart` 
- 🌲 Nature → `bi-tree`
- 🏛️ Architecture → `bi-building`
- 🎭 Événements → `bi-calendar-event`
- 🌅 Paysages → `bi-image`

---

### 📸 Ajouter une photo

1. Cliquez sur l'onglet **Ajouter une photo**
2. **Sélectionnez une photo** depuis votre ordinateur
   - Formats acceptés : JPG, PNG, GIF, WEBP
   - Taille max : 5MB
3. Remplissez les informations :
   - **Titre** : Un titre accrocheur
   - **Description** : Décrivez votre photo, le contexte, la technique
   - **Catégorie** : Choisissez parmi vos catégories
   - **Prix** : Définissez le prix en euros
4. Cliquez sur **Publier la photo**

✅ Votre photo apparaîtra immédiatement dans la galerie !

---

### 📷 Gérer vos photos

Dans l'onglet **Mes Photos** :
- Vous voyez toutes vos photos publiées
- Chaque photo affiche :
  - 🏷️ Sa catégorie avec l'icône
  - 💰 Son prix
  - 📊 Le nombre de ventes
  - 🗑️ Un bouton pour la supprimer

**Pour supprimer une photo** :
1. Cliquez sur l'icône poubelle (🗑️) sur la photo
2. Confirmez la suppression
3. La photo disparaît et le fichier est supprimé du serveur

---

## 🛍️ Guide Client

### Se connecter en tant que client

1. Allez sur `http://localhost/Photo4u/login.php`
2. Utilisez les identifiants : **leo** / **letanneur**
3. Vous serez redirigé vers votre dashboard client

### 📊 Comprendre votre dashboard

#### Statistiques (en haut)
- 🛒 **Mes achats** : Nombre de photos que vous possédez
- 💰 **Total dépensé** : Montant total de vos achats

#### Onglets de navigation
- **Parcourir les photos** : Voir et acheter des photos
- **Mes achats** : Accéder à vos photos achetées

---

### 🔍 Parcourir et acheter des photos

1. Vous êtes sur l'onglet **Parcourir les photos** par défaut
2. Utilisez les **filtres de catégories** en haut pour affiner votre recherche
   - Cliquez sur "Toutes" pour voir tout
   - Cliquez sur une catégorie spécifique pour filtrer

3. Pour chaque photo, vous voyez :
   - 📸 L'aperçu de la photo
   - 🏷️ La catégorie
   - 💰 Le prix
   - 👤 Le nom du photographe
   - 📝 La description

4. **Si vous ne possédez pas la photo** :
   - Bouton bleu **Acheter** visible
   - Cliquez pour acheter instantanément

5. **Si vous possédez déjà la photo** :
   - Badge vert **Acheté** affiché
   - Bouton **Télécharger** disponible

---

### 📥 Accéder à vos photos achetées

1. Cliquez sur l'onglet **Mes achats (X)**
2. Vous voyez toutes vos photos achetées avec :
   - La date d'achat
   - Le prix payé
   - Le nom du photographe
   - Un bouton **Télécharger** pour chaque photo

3. **Pour télécharger une photo** :
   - Cliquez sur le bouton vert **Télécharger**
   - La photo est téléchargée sur votre ordinateur
   - Vous pouvez la télécharger autant de fois que vous voulez !

---

## 📝 S'inscrire sur la plateforme

### Nouvelle page d'inscription

1. Allez sur `http://localhost/Photo4u/register.php`
2. **Choisissez votre rôle** :
   - 🛒 **Acheter** : Pour acheter des photos (Client)
   - 📸 **Vendre** : Pour vendre vos photos (Photographe)

3. **Remplissez le formulaire** :
   - Nom d'utilisateur
   - Adresse email
   - Mot de passe (minimum 6 caractères)
   - Confirmation du mot de passe
   - ✅ Acceptez les conditions d'utilisation

4. Cliquez sur **Créer mon compte**
5. Vous pouvez ensuite vous connecter !

---

## 🔒 Sécurité

### Mots de passe
- Minimum 6 caractères
- Hashés avec bcrypt
- Jamais stockés en clair

### Achats
- Impossible d'acheter 2 fois la même photo
- Vérification automatique avant chaque achat

### Sessions
- Connexion sécurisée par session PHP
- Déconnexion automatique après fermeture du navigateur
- Protection contre les accès non autorisés

---

## ❓ Questions fréquentes (FAQ)

### Pour les photographes

**Q : Combien de catégories puis-je créer ?**
R : Illimité ! Créez autant de catégories que nécessaire pour organiser votre portfolio.

**Q : Puis-je modifier le prix d'une photo après publication ?**
R : Pour l'instant, non. Vous devez supprimer et republier la photo avec le nouveau prix.

**Q : Que se passe-t-il si je supprime une catégorie ?**
R : Les photos de cette catégorie restent accessibles mais n'auront plus de catégorie assignée.

**Q : Puis-je voir qui a acheté mes photos ?**
R : Actuellement non, vous voyez uniquement le nombre de ventes par photo.

### Pour les clients

**Q : Puis-je télécharger une photo plusieurs fois ?**
R : Oui ! Une fois achetée, vous pouvez télécharger autant de fois que vous voulez.

**Q : Comment retrouver une photo achetée ?**
R : Allez dans l'onglet "Mes achats" de votre dashboard.

**Q : Puis-je obtenir un remboursement ?**
R : Contactez l'administrateur via la page contact.

**Q : Y a-t-il une limite d'achats ?**
R : Non, vous pouvez acheter autant de photos que vous souhaitez.

### Général

**Q : Comment changer mon mot de passe ?**
R : Fonctionnalité à venir dans une prochaine version.

**Q : Puis-je changer mon rôle (de client à photographe) ?**
R : Actuellement non, contactez un administrateur.

**Q : Le site est-il accessible sur mobile ?**
R : Oui ! Tout le site est responsive et optimisé pour mobile.

---

## 🐛 Résolution de problèmes

### Problème : "Table 'categories' doesn't exist"
**Solution** : Vous n'avez pas exécuté le fichier `update_database.sql`. Voir Étape 1 ci-dessus.

### Problème : Mes photos ne s'affichent pas
**Solution** : Vérifiez que le dossier `images/` est accessible en écriture et que les fichiers y sont bien uploadés.

### Problème : Erreur lors de l'upload
**Solution** : 
- Vérifiez que votre fichier fait moins de 5MB
- Vérifiez le format (JPG, PNG, GIF, WEBP uniquement)
- Vérifiez que `upload_max_filesize` et `post_max_size` sont suffisants dans php.ini

### Problème : Je ne peux pas créer de catégorie
**Solution** : Vérifiez que vous êtes connecté en tant que photographe (pas client).

### Problème : "Duplicate entry" lors de la création de catégorie
**Solution** : Cette catégorie existe déjà. Choisissez un autre nom.

---

## 💡 Conseils et bonnes pratiques

### Pour les photographes

1. **Organisez dès le début** : Créez vos catégories avant d'ajouter des photos
2. **Soyez descriptif** : Rédigez des descriptions détaillées pour vos photos
3. **Prix cohérents** : Définissez des prix en fonction de la qualité et de la rareté
4. **Catégories pertinentes** : Ne créez pas trop de catégories, restez organisé
5. **Noms d'icônes** : Testez vos icônes sur https://icons.getbootstrap.com/

### Pour les clients

1. **Utilisez les filtres** : Gagnez du temps en filtrant par catégorie
2. **Vérifiez avant d'acheter** : Le badge "Acheté" évite les doublons
3. **Téléchargez rapidement** : Sauvegardez vos photos dès l'achat
4. **Explorez** : Découvrez différents photographes et styles

---

## 🎯 Raccourcis clavier (à venir)

Fonctionnalité prévue pour une prochaine version :
- `Ctrl + U` : Upload rapide (photographes)
- `Ctrl + K` : Recherche rapide (clients)
- `Esc` : Fermer les modales

---

## 📞 Besoin d'aide ?

- 📧 **Email** : contact@photo4u.com (simulation)
- 📱 **Téléphone** : +33 1 23 45 67 89 (simulation)
- 💬 **Page contact** : http://localhost/Photo4u/contact.php

---

**Dernière mise à jour** : Octobre 2025  
**Version** : 2.0.0
