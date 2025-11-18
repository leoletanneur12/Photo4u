<?php
require_once 'session.php';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mentions légales de Photo4u : informations sur l'éditeur, l'hébergeur et les contacts.">
    <title>Mentions légales - Photo4u</title>
    <link rel="icon" type="image/png" href="images/logo-photo4u.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo-photo4u.png" alt="Photo4u Logo" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php#accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h1 class="h4 mb-0">Mentions légales</h1>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Dernière mise à jour : 18/11/2025</p>

                    <h2 class="h5 mt-4">1. Éditeur du site</h2>
                    <p>
                        Photo4u<br>
                        Siège social : 36 rue Alfred de Musset, 11802 Carcassonne, France<br>
                        Contact : contact@photo4u.com | +33 1 23 45 67 89
                    </p>

                    <h2 class="h5 mt-4">2. Hébergement</h2>
                    <p>
                        Site en environnement de développement local (WAMP). Pour la mise en production, renseigner l'hébergeur (raison sociale, adresse, téléphone).
                    </p>

                    <h2 class="h5 mt-4">3. Directeur de la publication</h2>
                    <p>
                        Responsable de publication : Photo4u
                    </p>

                    <h2 class="h5 mt-4">4. Propriété intellectuelle</h2>
                    <p>
                        L'ensemble des contenus (textes, images, logos, marques) présents sur ce site est protégé par le droit d'auteur et la propriété intellectuelle. Toute reproduction ou représentation, totale ou partielle, sans autorisation est interdite.
                    </p>

                    <h2 class="h5 mt-4">5. Données personnelles</h2>
                    <p>
                        Le traitement des données personnelles est détaillé dans notre <a href="politique_confidentialite.php">Politique de confidentialité</a>. Vous y trouverez vos droits et la manière de les exercer.
                    </p>

                    <h2 class="h5 mt-4">6. Cookies</h2>
                    <p>
                        Pour en savoir plus sur l'usage des cookies, consultez notre <a href="politique_cookies.php">Politique de cookies</a>.
                    </p>

                    <h2 class="h5 mt-4">7. Contact</h2>
                    <p>
                        Pour toute question, vous pouvez nous écrire à : contact@photo4u.com
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
