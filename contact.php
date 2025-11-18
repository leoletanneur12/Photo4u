<?php
require_once 'session.php';

$success = '';
$error = '';

// Traiter l'envoi du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Tous les champs sont requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide.';
    } else {
        // Dans un vrai site, on enverrait un email ici
        // mail($to, $subject, $message, $headers);

        // Pour l'instant, on simule l'envoi
        $success = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.';

        // Réinitialiser les champs
        $name = $email = $subject = $message = '';
    }
}

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactez l'équipe Photo4u pour toute question ou assistance.">
    <title>Contact - Photo4u</title>
    <link rel="icon" type="image/png" href="images/logo-photo4u.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#accueil">Accueil</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#galerie">Galerie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#formules">Nos formules</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#tarifs">Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                </ul>
                <?php if (isLoggedIn()): ?>
                    <div class="dropdown ms-3">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user['username']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text"><strong>Rôle:</strong> <?php echo ucfirst($user['role']); ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (isAdmin()): ?>
                                <li><a class="dropdown-item" href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard Admin</a></li>
                            <?php elseif (isPhotographe()): ?>
                                <li><a class="dropdown-item" href="photographe_dashboard.php"><i class="bi bi-camera"></i> Mes Photos</a></li>
                            <?php elseif (isClient()): ?>
                                <li><a class="dropdown-item" href="client_dashboard.php"><i class="bi bi-bag"></i> Mes Achats</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php?logout=1"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="register.php" class="btn btn-outline-light ms-3">
                        <i class="bi bi-person-plus"></i> S'inscrire
                    </a>
                    <a href="login.php" class="btn btn-primary ms-2">
                        <i class="bi bi-box-arrow-in-right"></i> Se connecter
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Contact Section -->
    <section class="py-5 mt-5">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h2 class="mb-0"><i class="bi bi-envelope-fill me-2"></i>Contactez-nous</h2>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted mb-4">
                                Une question ? Un projet photo ? N'hésitez pas à nous contacter, nous vous répondrons dans les plus brefs délais.
                            </p>

                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if ($success): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nom complet *</label>
                                        <input type="text" class="form-control" id="name" name="name" required
                                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Sujet *</label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">-- Choisissez un sujet --</option>
                                        <option value="Demande de devis">Demande de devis</option>
                                        <option value="Question technique">Question technique</option>
                                        <option value="Shooting personnalisé">Shooting personnalisé</option>
                                        <option value="Collaboration photographe">Collaboration photographe</option>
                                        <option value="Problème avec une commande">Problème avec une commande</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="6" required
                                              placeholder="Décrivez votre demande en détail..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                </div>
                                <button type="submit" name="send_message" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-send me-2"></i>Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Informations de contact -->
                    <div class="row mt-4 g-4">
                        <div class="col-md-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="bi bi-geo-alt-fill text-primary" style="font-size: 2rem;"></i>
                                    <h5 class="mt-3">Adresse</h5>
                                    <p class="text-muted mb-0"><strong>Siège social</strong><br>36 rue Alfred de Musset<br>11802 Carcassonne, France</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="bi bi-telephone-fill text-primary" style="font-size: 2rem;"></i>
                                    <h5 class="mt-3">Téléphone</h5>
                                    <p class="text-muted mb-0">+33 1 23 45 67 89<br>Lun-Ven: 9h-18h</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="bi bi-envelope-fill text-primary" style="font-size: 2rem;"></i>
                                    <h5 class="mt-3">Email</h5>
                                    <p class="text-muted mb-0">contact@photo4u.com<br>Réponse sous 24h</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte - Siège social -->
                    <div class="card mt-4 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Localisation — Siège social</h5>
                        </div>
                        <div class="card-body p-0">
                            <div style="width: 100%; height: 380px;">
                                <iframe
                                    title="Carte du siège social"
                                    width="100%"
                                    height="100%"
                                    style="border:0;"
                                    loading="lazy"
                                    allowfullscreen
                                    referrerpolicy="no-referrer-when-downgrade"
                                    src="https://www.google.com/maps?q=36%20rue%20Alfred%20de%20Musset%2011802%20Carcassonne&output=embed">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>© 2025 - Photo4u</h5>
                    <p class="text-muted small">Votre photographe professionnel pour tous vos événements</p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Liens</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php#accueil" class="text-decoration-none text-muted hover-link">Accueil</a></li>
                        <li><a href="index.php#galerie" class="text-decoration-none text-muted hover-link">Galerie</a></li>
                        <li><a href="index.php#tarifs" class="text-decoration-none text-muted hover-link">Tarifs</a></li>
                        <li><a href="mentions_legales.php" class="text-decoration-none text-muted hover-link">Mentions légales</a></li>
                        <li><a href="politique_confidentialite.php" class="text-decoration-none text-muted hover-link">Politique de confidentialité</a></li>
                        <li><a href="politique_cookies.php" class="text-decoration-none text-muted hover-link">Politique de cookies</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Aides</h5>
                    <ul class="list-unstyled">
                        <li><a href="contact.php" class="text-decoration-none text-muted hover-link">Nous contacter</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-link">FAQ</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-link">Aide en ligne</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Suivez-nous</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0 text-muted">&copy; 2025 Photo4u - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
