<?php
require_once 'session.php';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Photo4u - Achetez des photos de qualité en crédits. Explorez les portfolios de nos photographes et gérez vos achats facilement.">
    <title>PhotoFor You - Votre photographe professionnel</title>
    <link rel="icon" type="image/png" href="images/logo-photo4u.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/logo-photo4u.png" alt="Photo4u Logo" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#accueil">Accueil</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#galerie">Galerie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#formules">Nos formules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tarifs">Tarifs</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
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

    <!-- Hero Section -->
    <section id="accueil" class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 text-white mb-3 animate-fade-in">PhotoForYou</h1>
                    <p class="lead text-white mb-4 animate-fade-in">Que vous soyez un particulier ou une entreprise, nous immortalisons vos moments précieux</p>
                    
                    <?php if (!isLoggedIn()): ?>
                        <div class="alert alert-light d-inline-block animate-fade-in mb-4" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Connectez-vous</strong> pour accéder à notre galerie complète et nos tarifs !
                        </div>
                    <?php else: ?>
                        <p class="text-white-50 mb-4 animate-fade-in">Découvrez nos images et sublimez votre story Snapchat ou feed Instagram grâce à notre photographe</p>
                    <?php endif; ?>
                    
                    <?php if (!isLoggedIn()): ?>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="register.php" class="btn btn-primary btn-lg animate-fade-in">
                                <i class="bi bi-person-plus me-2"></i> S'inscrire gratuitement
                            </a>
                            <a href="login.php" class="btn btn-outline-light btn-lg animate-fade-in">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <i class="bi bi-chevron-down"></i>
        </div>
    </section>

    <!-- Mini Gallery Preview - VISIBLE POUR TOUS (visiteurs + connectés) -->
    <section class="mini-gallery py-5">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="h3 mb-2">Découvrez nos catégories de photo</h2>
                <p class="text-muted mb-0">Paysages, portraits et événements — explorez notre sélection</p>
                <?php if (!isLoggedIn()): ?>
                    <div class="alert alert-info mt-3 d-inline-block">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Aperçu limité</strong> — Inscrivez-vous pour accéder à toute la galerie !
                    </div>
                <?php endif; ?>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="gallery-card <?php echo !isLoggedIn() ? 'preview-mode' : ''; ?>">
                        <img src="images/paysage2.jpg" alt="Paysage" class="img-fluid rounded">
                        <div class="gallery-overlay">
                            <h5>Paysages</h5>
                            <?php if (!isLoggedIn()): ?>
                                <p class="small mb-2"><i class="bi bi-lock-fill"></i> Connectez-vous pour voir</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-card <?php echo !isLoggedIn() ? 'preview-mode' : ''; ?>">
                        <img src="images/portrait2.jpg" alt="Portrait" class="img-fluid rounded">
                        <div class="gallery-overlay">
                            <h5>Portraits</h5>
                            <?php if (!isLoggedIn()): ?>
                                <p class="small mb-2"><i class="bi bi-lock-fill"></i> Connectez-vous pour voir</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-card <?php echo !isLoggedIn() ? 'preview-mode' : ''; ?>">
                        <img src="images/evenement2.jpg" alt="Événement" class="img-fluid rounded">
                        <div class="gallery-overlay">
                            <h5>Événements</h5>
                            <?php if (!isLoggedIn()): ?>
                                <p class="small mb-2"><i class="bi bi-lock-fill"></i> Connectez-vous pour voir</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!isLoggedIn()): ?>
                <div class="text-center mt-4">
                    <a href="register.php" class="btn btn-primary btn-lg me-2">
                        <i class="bi bi-person-plus"></i> S'inscrire pour voir plus
                    </a>
                    <a href="login.php" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i> Se connecter
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if (isLoggedIn()): ?>

    <!-- Galerie complète -->
    <section id="galerie" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-4">Galerie</h2>
            <p class="text-center text-muted mb-5">Une sélection de nos dernières prises de vues</p>
            <div class="row g-4">
                <!-- Paysages -->
                <div class="col-sm-6 col-lg-4">
                    <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" data-gallery-src="images/paysage.jpg" data-gallery-alt="Paysage">
                        <img src="images/paysage.jpg" class="img-fluid rounded" alt="Paysage">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" data-gallery-src="images/paysage2.jpg" data-gallery-alt="Paysage 2">
                        <img src="images/paysage2.jpg" class="img-fluid rounded" alt="Paysage 2">
                    </a>
                </div>
                <!-- Portraits -->
                <div class="col-sm-6 col-lg-4">
                    <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" data-gallery-src="images/portrait.jpg" data-gallery-alt="Portrait">
                        <img src="images/portrait.jpg" class="img-fluid rounded" alt="Portrait">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" data-gallery-src="images/portrait2.jpg" data-gallery-alt="Portrait 2">
                        <img src="images/portrait2.jpg" class="img-fluid rounded" alt="Portrait 2">
                    </a>
                </div>
                <!-- Événements -->
                <div class="col-sm-6 col-lg-4">
                    <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" data-gallery-src="images/evenement.jpg" data-gallery-alt="Mariage">
                        <img src="images/evenement.jpg" class="img-fluid rounded" alt="Mariage">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" data-gallery-src="images/evenement2.jpg" data-gallery-alt="Ballon d'or">
                        <img src="images/evenement2.jpg" class="img-fluid rounded" alt="Ballon d'or">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Formules Section -->
    <section id="formules" class="formules-section py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nos Formules</h2>
            <div class="row g-4">
                <!-- Formule Paysages -->
                <div class="col-lg-4 col-md-6">
                    <div class="formule-card formule-paysages">
                        <div class="formule-image">
                            <img src="images/paysage.jpg" alt="Les paysages">
                        </div>
                        <div class="formule-content" data-label="Paysage">
                            <h3>Les paysages</h3>
                            <p>Immortalisez la beauté de la nature avec nos shootings paysages. Des panoramas à couper le souffle pour vos réseaux sociaux.</p>
                            <button class="btn btn-formule btn-warning" data-bs-toggle="modal" data-bs-target="#paysagesModal">
                                <i class="bi bi-info-circle me-2"></i> En savoir +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formule Portraits -->
                <div class="col-lg-4 col-md-6">
                    <div class="formule-card formule-portraits">
                        <div class="formule-image">
                            <img src="images/portrait.jpg" alt="Les portraits">
                        </div>
                        <div class="formule-content" data-label="Portrait">
                            <h3>Les portraits</h3>
                            <p>Sublimez votre image avec un shooting portrait professionnel. Parfait pour vos profils professionnels ou photos de famille.</p>
                            <button class="btn btn-formule btn-success" data-bs-toggle="modal" data-bs-target="#portraitsModal">
                                <i class="bi bi-info-circle me-2"></i> En savoir +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formule Événements -->
                <div class="col-lg-4 col-md-6">
                    <div class="formule-card formule-evenements">
                        <div class="formule-image">
                            <img src="images/evenement.jpg" alt="Les événements">
                        </div>
                        <div class="formule-content" data-label="Événement">
                            <h3>Les événements</h3>
                            <p>Capturez chaque instant de vos événements spéciaux. Mariages, anniversaires, événements d'entreprise... nous sommes là.</p>
                            <button class="btn btn-formule btn-danger" data-bs-toggle="modal" data-bs-target="#evenementsModal">
                                <i class="bi bi-info-circle me-2"></i> En savoir +
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action Buttons -->
            <div class="row mt-5 g-3">
                <div class="col-lg-4">
                    <button class="btn btn-lg btn-warning w-100 py-3" data-bs-toggle="modal" data-bs-target="#paysagesModal">
                        <i class="bi bi-image me-2"></i> Découvrir nos paysages
                    </button>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-lg btn-success w-100 py-3" data-bs-toggle="modal" data-bs-target="#portraitsModal">
                        <i class="bi bi-person-circle me-2"></i> Voir nos portraits
                    </button>
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-lg btn-danger w-100 py-3" data-bs-toggle="modal" data-bs-target="#evenementsModal">
                        <i class="bi bi-calendar-event me-2"></i> Nos événements
                    </button>
                </div>
            </div>
        </div>
    </section>

    <?php endif; // Fin de isLoggedIn() pour formules ?>

    <!-- Tarifs Section - VISIBLE POUR TOUS -->
    <section id="tarifs" class="tarifs-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-3">Tarifs</h2>
            <p class="text-center text-muted mb-5">Une tarification flexible pour tous vos besoins</p>
            
            <?php if (!isLoggedIn()): ?>
                <div class="alert alert-primary text-center mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Inscrivez-vous</strong> pour profiter de nos tarifs et acheter vos photos !
                </div>
            <?php endif; ?>

            <!-- Première rangée de tarifs -->
            <div class="row g-4 mb-4">
                <!-- Formule Essai -->
                <div class="col-lg-4">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>Essai</h3>
                            <div class="price">
                                <span class="amount">0€</span>
                                <span class="period">/ gratuit</span>
                            </div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill text-success"></i> 5 crédits offerts</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Aperçu flou de la galerie</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Usage personnel uniquement</li>
                            <li><i class="bi bi-x-circle-fill text-danger"></i> Pas de support prioritaire</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#essaiModal">Commencer l'essai</button>
                    </div>
                </div>

                <!-- Formule Découverte -->
                <div class="col-lg-4">
                    <div class="pricing-card pricing-popular">
                        <div class="badge-popular">Populaire</div>
                        <div class="pricing-header">
                            <h3>Découverte</h3>
                            <div class="price">
                                <span class="amount">15€</span>
                                <span class="period">/ mois</span>
                            </div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill text-success"></i> 20 crédits inclus</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> 15% de réduction sur les photos</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Accès illimité à la galerie</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Téléchargement haute résolution</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Support par email</li>
                        </ul>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">S'abonner</button>
                    </div>
                </div>

                <!-- Formule Pro -->
                <div class="col-lg-4">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>Pro</h3>
                            <div class="price">
                                <span class="amount">39€</span>
                                <span class="period">/ mois</span>
                            </div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill text-success"></i> 50 crédits inclus</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> 30% de réduction sur les photos</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Accès illimité + nouveautés</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Téléchargement HD + RAW</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Usage commercial autorisé</li>
                            <li><i class="bi bi-check-circle-fill text-success"></i> Support prioritaire 24/7</li>
                        </ul>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">S'abonner Pro</button>
                    </div>
                </div>
            </div>

            <!-- Seconde rangée de tarifs -->
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="pricing-card-alt bg-warning text-dark">
                        <h4><i class="bi bi-image me-2"></i> Shooting Paysage à 1,50€</h4>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="pricing-card-alt bg-success text-white">
                        <h4><i class="bi bi-person-circle me-2"></i> Shooting Portrait à 2,00€</h4>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="pricing-card-alt bg-danger text-white">
                        <h4><i class="bi bi-calendar-event me-2"></i> Shooting Event à 3,00€/20m</h4>
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
                        <li><a href="#accueil" class="text-decoration-none text-muted hover-link">Mes promotions</a></li>
                        <li><a href="#galerie" class="text-decoration-none text-muted hover-link">Les photographies</a></li>
                        <li><a href="#tarifs" class="text-decoration-none text-muted hover-link">Notre charte qualité</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Aides</h5>
                    <ul class="list-unstyled">
                        <li><a href="#contact" class="text-decoration-none text-muted hover-link">Nous contacter</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-link">Tuto</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-link">Aide en ligne</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Nous connaître</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-muted hover-link">Adresse</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-link">Contact</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0 text-muted">&copy; 2025 Photo4u - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <!-- Modals -->
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Se connecter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none">Mot de passe oublié ?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Discover Modal -->
    <div class="modal fade" id="discoverModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Découvrir PhotoFor You</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Présentation" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formules Modals -->
    <div class="modal fade" id="essaiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Faire un essai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Profitez d'un essai gratuit avec 5 photos offertes pour découvrir notre service.</p>
                    <form>
                        <div class="mb-3">
                            <label for="essaiEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="essaiEmail" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Démarrer l'essai</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="paysagesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Les Paysages</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Nos shootings paysages capturent la beauté naturelle sous tous ses angles. Des levers de soleil magiques aux panoramas époustouflants.</p>
                    <ul>
                        <li>Photos haute résolution</li>
                        <li>Retouche professionnelle incluse</li>
                        <li>Formats optimisés pour réseaux sociaux</li>
                    </ul>
                    <p class="text-muted">Tarif : 1,50€ par shooting</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="portraitsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Les Portraits</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Sublimez votre image avec nos portraits professionnels. Idéal pour vos profils LinkedIn, photos de famille ou book personnel.</p>
                    <ul>
                        <li>Shooting personnalisé</li>
                        <li>Conseils vestimentaires et pose</li>
                        <li>Retouches beauté incluses</li>
                    </ul>
                    <p class="text-muted">Tarif : 2,00€ par shooting</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="evenementsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Les Événements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Immortalisez vos événements spéciaux avec notre couverture photo complète. Mariages, anniversaires, événements d'entreprise...</p>
                    <ul>
                        <li>Couverture complète de l'événement</li>
                        <li>Photos candides et posées</li>
                        <li>Livraison rapide en galerie en ligne</li>
                    </ul>
                    <p class="text-muted">Tarif : 3,00€ / 20 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/auth.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
