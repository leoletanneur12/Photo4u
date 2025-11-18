<?php
require_once 'session.php';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Politique de confidentialité de Photo4u conforme au RGPD : données collectées, finalités, droits et conservation.">
    <title>Politique de confidentialité - Photo4u</title>
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
                    <h1 class="h4 mb-0">Politique de confidentialité</h1>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Dernière mise à jour : 18/11/2025</p>

                    <h2 class="h5 mt-4">1. Responsable du traitement</h2>
                    <p>
                        Photo4u — Siège social : 36 rue Alfred de Musset, 11802 Carcassonne, France.<br>
                        Contact : contact@photo4u.com
                    </p>

                    <h2 class="h5 mt-4">2. Données collectées</h2>
                    <p>
                        Nous collectons les données que vous nous fournissez (ex. : nom, email, mot de passe chiffré, préférences, messages via le formulaire de contact) et des données liées à l'usage du service (ex. : historique d'achats, crédits, journaux techniques).
                    </p>

                    <h2 class="h5 mt-4">3. Finalités et bases légales</h2>
                    <ul>
                        <li>Fourniture du service (gestion des comptes, achats, crédits) — Exécution du contrat.</li>
                        <li>Support client et communication — Intérêt légitime / Consentement selon les cas.</li>
                        <li>Sécurité, prévention de la fraude — Intérêt légitime.</li>
                        <li>Obligations légales (facturation, comptabilité) — Obligation légale.</li>
                    </ul>

                    <h2 class="h5 mt-4">4. Durées de conservation</h2>
                    <ul>
                        <li>Compte utilisateur: durée d'utilisation + 3 ans après inactivité.</li>
                        <li>Données de facturation/achat: 10 ans (obligations légales).</li>
                        <li>Logs techniques: jusqu'à 12 mois.</li>
                        <li>Messages de contact: 3 ans après le dernier contact.</li>
                    </ul>

                    <h2 class="h5 mt-4">5. Destinataires</h2>
                    <p>
                        Accès restreint à l'équipe Photo4u et aux sous-traitants techniques nécessaires (hébergement, paiement, emailing). Aucun transfert non justifié ni revente de données.
                    </p>

                    <h2 class="h5 mt-4">6. Transferts hors UE</h2>
                    <p>
                        En cas de transfert hors UE, nous nous engageons à utiliser des garanties appropriées (clauses contractuelles types, prestataires conformes).
                    </p>

                    <h2 class="h5 mt-4">7. Vos droits</h2>
                    <p>
                        Vous disposez des droits d'accès, rectification, effacement, opposition, limitation, portabilité. Pour exercer vos droits : contact@photo4u.com. Vous pouvez aussi introduire une réclamation auprès de la CNIL (www.cnil.fr).
                    </p>

                    <h2 class="h5 mt-4">8. Cookies</h2>
                    <p>
                        Voir notre <a href="politique_cookies.php">Politique de cookies</a>. Les cookies strictement nécessaires au fonctionnement du site peuvent être déposés sans consentement. Les autres cookies (mesure d'audience, marketing) sont soumis à votre consentement préalable.
                    </p>

                    <h2 class="h5 mt-4">9. Sécurité</h2>
                    <p>
                        Mesures raisonnables de sécurité sont mises en place (chiffrement des mots de passe, contrôle d'accès, mises à jour). Aucun système n'étant invulnérable, nous vous recommandons des mots de passe forts et uniques.
                    </p>

                    <h2 class="h5 mt-4">10. Contact DPO</h2>
                    <p>
                        Pour toute question relative à la protection des données : contact@photo4u.com
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="footer bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <small>&copy; 2025 Photo4u</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
