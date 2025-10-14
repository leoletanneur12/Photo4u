# Instructions pour la mise à jour de la base de données

## 📋 Étapes à suivre

### 1. Ouvrir phpMyAdmin
Accédez à http://localhost/phpmyadmin

### 2. Sélectionner la base de données
Cliquez sur `photo4u` dans la liste des bases de données

### 3. Exécuter les requêtes SQL
Cliquez sur l'onglet **SQL** et collez le contenu du fichier `update_database.sql`

### 4. Cliquez sur **Exécuter**

## ✅ Ce qui sera ajouté :

### Nouvelle table : `categories`
- Permet aux photographes de créer leurs propres catégories
- Colonnes : id, photographer_id, name, description, icon, created_at
- Chaque photographe peut avoir ses catégories uniques

### Modification de la table `photos`
- Ajout de la colonne `category_id` (référence vers categories)
- La colonne `category` (texte) est conservée pour compatibilité
- Liaison avec la table categories pour une meilleure organisation

### Données de démonstration
- 5 catégories créées pour le photographe de test (ID 2)
- Les photos existantes sont automatiquement associées aux nouvelles catégories

## 🎯 Nouvelles fonctionnalités activées :

### Pour les photographes :
- ✅ Créer des catégories personnalisées avec icônes
- ✅ Organiser leurs photos par catégories
- ✅ Voir les statistiques de ventes par photo
- ✅ Interface à onglets : Photos / Catégories / Ajouter

### Pour les clients :
- ✅ Parcourir toutes les photos disponibles
- ✅ Filtrer par catégories
- ✅ Voir quelles photos sont déjà achetées
- ✅ Télécharger les photos achetées
- ✅ Statistiques d'achats

### Page d'inscription améliorée :
- ✅ Design moderne type e-commerce
- ✅ Avantages affichés sur le côté
- ✅ Sélection visuelle du rôle (Client/Photographe)
- ✅ Validation avec conditions d'utilisation

## 🔍 Vérification

Après avoir exécuté les requêtes, vérifiez que :
1. La table `categories` existe
2. La colonne `category_id` est présente dans la table `photos`
3. Les 5 catégories de démonstration sont créées
4. Les photos existantes ont un `category_id` non nul

## 🚨 En cas d'erreur

Si vous rencontrez une erreur du type "Table 'categories' already exists" :
- C'est normal si vous réexécutez le script
- Les données existantes ne seront pas écrasées grâce à `IF NOT EXISTS`

Si vous avez une erreur sur `ALTER TABLE photos` :
- La colonne `category_id` existe peut-être déjà
- Vous pouvez ignorer cette erreur sans problème
