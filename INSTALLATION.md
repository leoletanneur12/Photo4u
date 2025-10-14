# 🚀 Installation Rapide - Photo4u

## Étape 1: Importer la base de données

### Méthode 1: Via phpMyAdmin (Recommandé)
1. Ouvrez phpMyAdmin: `http://localhost/phpmyadmin`
2. Cliquez sur "Nouvelle base de données" (ou "New")
3. Nommez-la `photo4u`
4. Sélectionnez `utf8mb4_unicode_ci` comme interclassement
5. Cliquez sur "Créer"
6. Sélectionnez la BDD `photo4u` dans la liste de gauche
7. Cliquez sur l'onglet "Importer"
8. Choisissez le fichier `database.sql`
9. Cliquez sur "Exécuter"

### Méthode 2: Via ligne de commande
```bash
# Ouvrir PowerShell dans le dossier du projet
cd C:\wamp64\www\Photo4u

# Importer la base de données (remplacer root par votre utilisateur MySQL si différent)
mysql -u root -p < database.sql
```

## Étape 2: Vérifier la configuration

Ouvrir `config.php` et vérifier:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'photo4u');
define('DB_USER', 'root');
define('DB_PASS', ''); // Ajouter votre mot de passe MySQL si nécessaire
```

## Étape 3: Démarrer WAMP

1. Lancez WAMP
2. Vérifiez que l'icône est verte
3. Assurez-vous qu'Apache et MySQL sont démarrés

## Étape 4: Accéder au site

Ouvrez votre navigateur et allez sur:
```
http://localhost/Photo4u/index.php
```

## 🔐 Comptes de test

| Rôle | Identifiant | Mot de passe | Description |
|------|-------------|--------------|-------------|
| **Admin** | admin | admin | Accès complet (gestion users/photos) |
| **Photographe** | photo | photo | Upload et gestion de photos |
| **Client** | leo | letanneur | Achat et téléchargement de photos |

## ✅ Test d'installation

1. Visiteur (non connecté):
   - Ouvrez `http://localhost/Photo4u/`
   - Vous devriez voir seulement la page d'accueil avec boutons S'inscrire/Se connecter
   
2. Inscription:
   - Cliquez sur "S'inscrire"
   - Créez un nouveau compte
   - Vous serez redirigé vers le dashboard client

3. Connexion Photographe:
   - Connectez-vous avec `photo` / `photo`
   - Vous accédez au dashboard photographe
   - Testez l'upload d'une photo

4. Connexion Admin:
   - Connectez-vous avec `admin` / `admin`
   - Vous accédez au dashboard admin
   - Vous voyez les statistiques et tous les users

## 🐛 Problèmes courants

### "Erreur de connexion à la base de données"
- Vérifiez que MySQL est démarré dans WAMP
- Vérifiez que la base `photo4u` existe
- Vérifiez les credentials dans `config.php`

### "Call to undefined function password_verify()"
- Mettez à jour PHP vers version 7.4 ou supérieure

### Les images ne s'uploadent pas
- Vérifiez les permissions du dossier `images/`
- Windows: `icacls "C:\wamp64\www\Photo4u\images" /grant Users:F`

### Page blanche
- Activez l'affichage des erreurs PHP dans WAMP
- Consultez les logs Apache/PHP

## 📝 Prochaines étapes

1. Personnaliser les prix et catégories
2. Ajouter vos propres photos
3. Modifier le design si nécessaire
4. Configurer un système de paiement réel (Stripe, PayPal)
5. Ajouter l'envoi d'emails (confirmation d'achat, etc.)

## 🎉 C'est tout !

Votre site Photo4u est maintenant opérationnel avec:
- ✅ Système d'authentification complet
- ✅ 3 dashboards (Admin, Photographe, Client)
- ✅ Gestion des photos et achats
- ✅ Accès restreint par rôle
- ✅ Interface moderne avec Bootstrap 5

Bon développement ! 🚀
