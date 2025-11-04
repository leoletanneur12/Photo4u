<?php
require_once 'config.php';
require_once 'session.php';

requireLogin();
if (!isClient() && !isAdmin()) {
    header('Location: index.php');
    exit();
}

$user = getCurrentUser();
$message = '';
$error = '';
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'browse';

// Traiter la recharge de crédits
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recharge_credits'])) {
    $postedAmount = $_POST['recharge_amount'] ?? $_POST['amount'] ?? null;
    $amount = $postedAmount !== null ? floatval($postedAmount) : 0.0;
    
    if ($amount >= 5 && $amount <= 500) {
        try {
            $pdo->beginTransaction();
            
            // Mettre à jour les crédits
            $stmt = $pdo->prepare("UPDATE users SET credits = credits + ? WHERE id = ?");
            $stmt->execute([$amount, $user['id']]);
            
            // Récupérer le nouveau solde
            $stmt = $pdo->prepare("SELECT credits FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $new_balance = $stmt->fetchColumn();
            
            // Enregistrer la transaction
            $stmt = $pdo->prepare("INSERT INTO credit_transactions (user_id, amount, type, description, balance_after) VALUES (?, ?, 'recharge', ?, ?)");
            $stmt->execute([$user['id'], $amount, "Recharge de crédits", $new_balance]);
            
            $pdo->commit();
            $user['credits'] = $new_balance; // Mettre à jour en session
            $_SESSION['credits'] = (float) $new_balance;
            $message = "Recharge de " . number_format($amount, 2) . "€ effectuée avec succès !";
        } catch(PDOException $e) {
            $pdo->rollBack();
            $error = "Erreur lors de la recharge : " . $e->getMessage();
        }
    } else {
        $error = "Le montant doit être entre 5€ et 500€.";
    }
}

// Traiter l'achat de photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_photo'])) {
    $photo_id = intval($_POST['photo_id']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM photos WHERE id = ?");
        $stmt->execute([$photo_id]);
        $photo = $stmt->fetch();
        
        if ($photo) {
            // Vérifier si déjà acheté
            $stmt = $pdo->prepare("SELECT * FROM purchases WHERE client_id = ? AND photo_id = ?");
            $stmt->execute([$user['id'], $photo_id]);
            
            if ($stmt->fetch()) {
                $error = "Vous possédez déjà cette photo.";
            } else {
                // Vérifier si assez de crédits
                if ($user['credits'] >= $photo['price']) {
                    $pdo->beginTransaction();
                    
                    // Déduire les crédits
                    $stmt = $pdo->prepare("UPDATE users SET credits = credits - ? WHERE id = ?");
                    $stmt->execute([$photo['price'], $user['id']]);
                    
                    // Récupérer le nouveau solde
                    $stmt = $pdo->prepare("SELECT credits FROM users WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    $new_balance = $stmt->fetchColumn();
                    
                    // Enregistrer la transaction
                    $stmt = $pdo->prepare("INSERT INTO credit_transactions (user_id, amount, type, description, balance_after) VALUES (?, ?, 'purchase', ?, ?)");
                    $stmt->execute([$user['id'], -$photo['price'], "Achat photo: " . $photo['title'], $new_balance]);
                    
                    // Enregistrer l'achat
                    $stmt = $pdo->prepare("INSERT INTO purchases (client_id, photo_id, price) VALUES (?, ?, ?)");
                    $stmt->execute([$user['id'], $photo_id, $photo['price']]);
                    
                    $pdo->commit();
                    $user['credits'] = $new_balance; // Mettre à jour en session
                    $_SESSION['credits'] = (float) $new_balance;
                    $message = "Photo achetée avec succès ! Retrouvez-la dans 'Mes Achats'. Crédits restants: " . number_format($new_balance, 2) . "€";
                } else {
                    $error = "Crédits insuffisants. Solde: " . number_format($user['credits'], 2) . "€ - Prix: " . number_format($photo['price'], 2) . "€";
                }
            }
        }
    } catch(PDOException $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer toutes les photos disponibles
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
try {
    if ($selectedCategory === 'all') {
        $stmt = $pdo->prepare("
            SELECT p.*, u.username as photographer_name, c.name as category_name, c.icon as category_icon
            FROM photos p
            JOIN users u ON p.photographer_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("
            SELECT p.*, u.username as photographer_name, c.name as category_name, c.icon as category_icon
            FROM photos p
            JOIN users u ON p.photographer_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE c.name = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$selectedCategory]);
    }
    $available_photos = $stmt->fetchAll();
} catch(PDOException $e) {
    $available_photos = [];
}

// Récupérer toutes les catégories disponibles
try {
    $stmt = $pdo->query("SELECT DISTINCT name, icon FROM categories ORDER BY name");
    $all_categories = $stmt->fetchAll();
} catch(PDOException $e) {
    $all_categories = [];
}

// Récupérer les photos achetées
try {
    $stmt = $pdo->prepare("
        SELECT p.*, ph.title, ph.description, ph.filename, ph.category, ph.price, p.purchase_date,
               u.username as photographer_name, c.name as category_name, c.icon as category_icon
        FROM purchases p
        JOIN photos ph ON p.photo_id = ph.id
        JOIN users u ON ph.photographer_id = u.id
        LEFT JOIN categories c ON ph.category_id = c.id
        WHERE p.client_id = ?
        ORDER BY p.purchase_date DESC
    ");
    $stmt->execute([$user['id']]);
    $my_purchases = $stmt->fetchAll();
} catch(PDOException $e) {
    $my_purchases = [];
}

// Statistiques
$total_purchases = count($my_purchases);
$total_spent = array_sum(array_column($my_purchases, 'price'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client - Photo4u</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .photo-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .photo-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .category-filter {
            cursor: pointer;
            transition: all 0.3s;
        }
        .category-filter:hover {
            background: var(--primary-color) !important;
            color: white !important;
        }
        .category-filter.active {
            background: var(--primary-color) !important;
            color: white !important;
        }
        .stats-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }
        .purchased-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            z-index: 10;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo-photo4u.svg" alt="Photo4u Logo" height="50">
            </a>
            <span class="navbar-text text-white me-auto ms-3">
                <i class="bi bi-bag-heart-fill me-2"></i>Espace Client
            </span>
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user['username']); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="index.php"><i class="bi bi-house"></i> Accueil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4"><i class="bi bi-bag-heart me-2"></i>Bienvenue, <?php echo htmlspecialchars($user['username']); ?> !</h1>
                
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h3 class="h5 mb-2"><i class="bi bi-wallet2"></i> Mes crédits</h3>
                    <h2 class="display-5"><?php echo number_format($user['credits'], 2); ?>€</h2>
                    <button class="btn btn-light btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#rechargeModal">
                        <i class="bi bi-plus-circle"></i> Recharger
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-badge">
                    <h3 class="h5 mb-2"><i class="bi bi-bag-check"></i> Mes achats</h3>
                    <h2 class="display-5"><?php echo $total_purchases; ?> photo(s)</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h3 class="h5 mb-2"><i class="bi bi-currency-euro"></i> Total dépensé</h3>
                    <h2 class="display-5"><?php echo number_format($total_spent, 2); ?>€</h2>
                </div>
            </div>
        </div>

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?php echo $activeTab === 'browse' ? 'active' : ''; ?>" href="?tab=browse">
                    <i class="bi bi-shop"></i> Parcourir les photos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $activeTab === 'purchases' ? 'active' : ''; ?>" href="?tab=purchases">
                    <i class="bi bi-bag-check"></i> Mes achats (<?php echo $total_purchases; ?>)
                </a>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content">
            <!-- Onglet Parcourir -->
            <?php if ($activeTab === 'browse'): ?>
                <!-- Filtres par catégorie -->
                <div class="mb-4">
                    <h5 class="mb-3"><i class="bi bi-funnel"></i> Filtrer par catégorie</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="?tab=browse&category=all" 
                           class="badge category-filter <?php echo $selectedCategory === 'all' ? 'active' : 'bg-secondary'; ?> p-2 text-decoration-none">
                            <i class="bi bi-grid"></i> Toutes
                        </a>
                        <?php foreach ($all_categories as $cat): ?>
                            <a href="?tab=browse&category=<?php echo urlencode($cat['name']); ?>" 
                               class="badge category-filter <?php echo $selectedCategory === $cat['name'] ? 'active' : 'bg-secondary'; ?> p-2 text-decoration-none">
                                <i class="<?php echo htmlspecialchars($cat['icon']); ?>"></i> 
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Galerie de photos -->
                <div class="row">
                    <?php if (empty($available_photos)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Aucune photo disponible dans cette catégorie pour le moment.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php 
                        // Vérifier quelles photos sont déjà achetées
                        $purchased_ids = array_column($my_purchases, 'photo_id');
                        ?>
                        <?php foreach ($available_photos as $photo): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card photo-card">
                                    <?php if (in_array($photo['id'], $purchased_ids)): ?>
                                        <span class="purchased-badge">
                                            <i class="bi bi-check-circle"></i> Acheté
                                        </span>
                                    <?php endif; ?>
                                    <img src="images/<?php echo htmlspecialchars($photo['filename']); ?>" 
                                         alt="<?php echo htmlspecialchars($photo['title']); ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary">
                                                <i class="<?php echo htmlspecialchars($photo['category_icon'] ?? 'bi-folder'); ?>"></i>
                                                <?php echo htmlspecialchars($photo['category_name'] ?? $photo['category']); ?>
                                            </span>
                                            <span class="badge bg-success"><?php echo number_format($photo['price'], 2); ?>€</span>
                                        </div>
                                        <h5 class="card-title"><?php echo htmlspecialchars($photo['title']); ?></h5>
                                        <p class="card-text text-muted small">
                                            <?php echo htmlspecialchars(substr($photo['description'], 0, 80)); ?>...
                                        </p>
                                        <p class="small text-muted mb-3">
                                            <i class="bi bi-camera"></i> Par <?php echo htmlspecialchars($photo['photographer_name']); ?>
                                        </p>
                                        <?php if (in_array($photo['id'], $purchased_ids)): ?>
                                            <a href="images/<?php echo htmlspecialchars($photo['filename']); ?>" 
                                               download class="btn btn-success w-100">
                                                <i class="bi bi-download"></i> Télécharger
                                            </a>
                                        <?php else: ?>
                                            <form method="POST" class="d-inline w-100">
                                                <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">
                                                <button type="submit" name="buy_photo" class="btn btn-primary w-100">
                                                    <i class="bi bi-cart-plus"></i> Acheter
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Onglet Mes achats -->
            <?php if ($activeTab === 'purchases'): ?>
                <div class="row">
                    <?php if (empty($my_purchases)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Vous n'avez pas encore acheté de photos. 
                                <a href="?tab=browse" class="alert-link">Parcourir la galerie</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($my_purchases as $purchase): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card photo-card">
                                    <img src="images/<?php echo htmlspecialchars($purchase['filename']); ?>" 
                                         alt="<?php echo htmlspecialchars($purchase['title']); ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary">
                                                <i class="<?php echo htmlspecialchars($purchase['category_icon'] ?? 'bi-folder'); ?>"></i>
                                                <?php echo htmlspecialchars($purchase['category_name'] ?? $purchase['category']); ?>
                                            </span>
                                            <span class="badge bg-success"><?php echo number_format($purchase['price'], 2); ?>€</span>
                                        </div>
                                        <h5 class="card-title"><?php echo htmlspecialchars($purchase['title']); ?></h5>
                                        <p class="card-text text-muted small">
                                            <?php echo htmlspecialchars(substr($purchase['description'], 0, 80)); ?>...
                                        </p>
                                        <p class="small text-muted mb-2">
                                            <i class="bi bi-camera"></i> Par <?php echo htmlspecialchars($purchase['photographer_name']); ?>
                                        </p>
                                        <p class="small text-muted mb-3">
                                            <i class="bi bi-calendar"></i> Acheté le <?php echo date('d/m/Y', strtotime($purchase['purchase_date'])); ?>
                                        </p>
                                        <a href="images/<?php echo htmlspecialchars($purchase['filename']); ?>" 
                                           download class="btn btn-success w-100">
                                            <i class="bi bi-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de recharge des crédits -->
    <div class="modal fade" id="rechargeModal" tabindex="-1" aria-labelledby="rechargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="rechargeModalLabel">
                        <i class="bi bi-wallet2 me-2"></i> Recharger mes crédits
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-center text-muted mb-4">Choisissez le montant de crédits à ajouter à votre compte</p>
                    
                    <form method="POST" action="" id="rechargeForm">
                        <div class="row g-3">
                            <!-- 5€ -->
                            <div class="col-md-4">
                                <label class="credit-pack-card">
                                    <input type="radio" name="recharge_amount" value="5" required>
                                    <div class="credit-pack-content">
                                        <div class="credit-icon">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                        <div class="credit-amount">5€</div>
                                        <div class="credit-desc">Débutant</div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- 10€ -->
                            <div class="col-md-4">
                                <label class="credit-pack-card">
                                    <input type="radio" name="recharge_amount" value="10" required>
                                    <div class="credit-pack-content">
                                        <div class="credit-icon">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                        <div class="credit-amount">10€</div>
                                        <div class="credit-desc">Standard</div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- 25€ -->
                            <div class="col-md-4">
                                <label class="credit-pack-card credit-popular">
                                    <input type="radio" name="recharge_amount" value="25" required>
                                    <div class="credit-pack-content">
                                        <span class="badge-popular-small">Populaire</span>
                                        <div class="credit-icon">
                                            <i class="bi bi-gem"></i>
                                        </div>
                                        <div class="credit-amount">25€</div>
                                        <div class="credit-desc">Populaire</div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- 35€ -->
                            <div class="col-md-4">
                                <label class="credit-pack-card">
                                    <input type="radio" name="recharge_amount" value="35" required>
                                    <div class="credit-pack-content">
                                        <div class="credit-icon">
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <div class="credit-amount">35€</div>
                                        <div class="credit-desc">Avancé</div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- 50€ -->
                            <div class="col-md-4">
                                <label class="credit-pack-card">
                                    <input type="radio" name="recharge_amount" value="50" required>
                                    <div class="credit-pack-content">
                                        <div class="credit-icon">
                                            <i class="bi bi-trophy-fill"></i>
                                        </div>
                                        <div class="credit-amount">50€</div>
                                        <div class="credit-desc">Pro</div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- 75€ -->
                            <div class="col-md-4">
                                <label class="credit-pack-card">
                                    <input type="radio" name="recharge_amount" value="75" required>
                                    <div class="credit-pack-content">
                                        <div class="credit-icon">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                        </div>
                                        <div class="credit-amount">75€</div>
                                        <div class="credit-desc">Expert</div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- 100€ -->
                            <div class="col-md-12">
                                <label class="credit-pack-card credit-premium">
                                    <input type="radio" name="recharge_amount" value="100" required>
                                    <div class="credit-pack-content-large">
                                        <span class="badge-premium">Meilleure valeur</span>
                                        <div class="credit-icon-large">
                                            <i class="bi bi-award-fill"></i>
                                        </div>
                                        <div class="credit-amount-large">100€</div>
                                        <div class="credit-desc-large">Pack Premium - Le meilleur rapport qualité/prix</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4 mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Paiement sécurisé :</strong> Les crédits seront ajoutés immédiatement après validation.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Annuler
                    </button>
                    <button type="submit" form="rechargeForm" name="recharge_credits" class="btn btn-primary btn-lg">
                        <i class="bi bi-credit-card me-2"></i> Valider la recharge
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .credit-pack-card {
            display: block;
            border: 3px solid #e0e0e0;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
            height: 100%;
        }
        
        .credit-pack-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        
        .credit-pack-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .credit-pack-card input[type="radio"]:checked + .credit-pack-content,
        .credit-pack-card input[type="radio"]:checked + .credit-pack-content-large {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .credit-pack-content {
            transition: all 0.3s;
            border-radius: 10px;
            padding: 15px;
        }
        
        .credit-icon {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #667eea;
        }
        
        .credit-pack-card input[type="radio"]:checked + .credit-pack-content .credit-icon {
            color: white;
        }
        
        .credit-amount {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .credit-desc {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .credit-popular {
            border-color: #f5576c;
        }
        
        .badge-popular-small {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f5576c;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .credit-premium {
            border-color: #ffc107;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .badge-premium {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ffc107;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        
        .credit-pack-content-large {
            transition: all 0.3s;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .credit-icon-large {
            font-size: 4rem;
            color: #ffc107;
        }
        
        .credit-pack-card input[type="radio"]:checked + .credit-pack-content-large .credit-icon-large {
            color: white;
        }
        
        .credit-amount-large {
            font-size: 3rem;
            font-weight: bold;
        }
        
        .credit-desc-large {
            font-size: 1rem;
            flex: 1;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
