<?php
require_once 'session.php';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Politique de cookies de Photo4u : types de cookies utilisés, consentement et gestion.">
    <title>Politique de cookies - Photo4u</title>
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
                    <h1 class="h4 mb-0">Politique de cookies</h1>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Dernière mise à jour : 18/11/2025</p>

                    <h2 class="h5 mt-4">1. Qu'est-ce qu'un cookie ?</h2>
                    <p>
                        Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, mobile, tablette) lors de la visite d'un site internet. Il permet au site de mémoriser des informations sur votre navigation.
                    </p>

                    <h2 class="h5 mt-4">2. Cookies que nous utilisons</h2>
                    <ul>
                        <li><strong>Cookies strictement nécessaires</strong> (ex. : session PHPSESSID) — indispensables au fonctionnement du site (authentification, panier/crédits, sécurité).</li>
                        <li><strong>Cookies de mesure d'audience</strong> — absents par défaut. Si déployés, ils nécessitent votre consentement préalable.</li>
                        <li><strong>Cookies tiers</strong> — par exemple contenus intégrés (cartes, polices). Leur dépôt dépend de vos paramètres et du fournisseur tiers.</li>
                    </ul>

                    <h2 class="h5 mt-4">3. Gestion du consentement</h2>
                    <p>
                        Les cookies strictement nécessaires sont déposés sans consentement. Les autres cookies sont déposés uniquement si vous y consentez. Un module de gestion du consentement (bandeau) peut être ajouté pour paramétrer vos choix.
                    </p>

                    <h2 class="h5 mt-4">4. Paramétrer votre navigateur</h2>
                    <p>
                        Vous pouvez configurer votre navigateur pour accepter, refuser ou supprimer des cookies :
                    </p>
                    <ul>
                        <li><a href="https://support.google.com/chrome/answer/95647?hl=fr" target="_blank" rel="noopener">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/fr/kb/activer-desactiver-cookies" target="_blank" rel="noopener">Mozilla Firefox</a></li>
                        <li><a href="https://support.microsoft.com/fr-fr/microsoft-edge/supprimer-les-cookies-dans-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener">Microsoft Edge</a></li>
                        <li><a href="https://support.apple.com/fr-fr/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Apple Safari</a></li>
                    </ul>

                    <h2 class="h5 mt-4">5. Contact</h2>
                    <p>Pour toute question relative aux cookies : contact@photo4u.com</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
