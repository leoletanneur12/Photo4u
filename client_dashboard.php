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
                // Enregistrer l'achat
                $stmt = $pdo->prepare("INSERT INTO purchases (client_id, photo_id, price) VALUES (?, ?, ?)");
                $stmt->execute([$user['id'], $photo_id, $photo['price']]);
                $message = "Photo achetée avec succès !";
            }
        }
    } catch(PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer les photos achetées
try {
    $stmt = $pdo->prepare("
        SELECT p.*, ph.title, ph.description, ph.filename, ph.category, ph.price, p.purchase_date
        FROM purchases p
        JOIN photos ph ON p.photo_id = ph.id
        WHERE p.client_id = ?
        ORDER BY p.purchase_date DESC
    ");
    $stmt->execute([$user['id']]);
    $purchases = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
    $purchases = [];
}

// Récupérer toutes les photos disponibles
try {
    $stmt = $pdo->query("SELECT * FROM photos ORDER BY created_at DESC");
    $all_photos = $stmt->fetchAll();
    
    // Identifier les photos déjà achetées
    $purchased_ids = array_column($purchases, 'photo_id');
} catch(PDOException $e) {
    $all_photos = [];
    $purchased_ids = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client - Photo4u</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo-photo4u.svg" alt="Photo4u Logo" height="40">
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user['username']); ?> (Client)
                </span>
                <a href="index.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-house"></i> Accueil
                </a>
                <a href="logout.php?logout=1" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="mb-4"><i class="bi bi-bag-fill me-2"></i>Mon Espace Client</h1>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Mes achats -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-bag-check me-2"></i>Mes photos achetées (<?php echo count($purchases); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($purchases)): ?>
                    <p class="text-muted text-center py-4">Vous n'avez encore acheté aucune photo.</p>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($purchases as $purchase): ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="card h-100">
                                    <img src="images/<?php echo htmlspecialchars($purchase['filename']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($purchase['title']); ?>" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo htmlspecialchars($purchase['title']); ?></h6>
                                        <p class="card-text small text-muted">
                                            Acheté le <?php echo date('d/m/Y', strtotime($purchase['purchase_date'])); ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-<?php 
                                                echo $purchase['category'] === 'paysage' ? 'warning' : 
                                                    ($purchase['category'] === 'portrait' ? 'success' : 'danger'); 
                                            ?>"><?php echo ucfirst($purchase['category']); ?></span>
                                            <strong><?php echo number_format($purchase['price'], 2); ?>€</strong>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <a href="images/<?php echo htmlspecialchars($purchase['filename']); ?>" download class="btn btn-primary btn-sm w-100">
                                            <i class="bi bi-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Photos disponibles à l'achat -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cart-plus me-2"></i>Photos disponibles à l'achat</h5>
            </div>
            <div class="card-body">
                <?php if (empty($all_photos)): ?>
                    <p class="text-muted text-center py-4">Aucune photo disponible pour le moment.</p>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($all_photos as $photo): ?>
                            <?php $is_purchased = in_array($photo['id'], $purchased_ids); ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="card h-100 <?php echo $is_purchased ? 'border-success' : ''; ?>">
                                    <?php if ($is_purchased): ?>
                                        <div class="badge bg-success position-absolute top-0 end-0 m-2">
                                            <i class="bi bi-check-circle"></i> Possédée
                                        </div>
                                    <?php endif; ?>
                                    <img src="images/<?php echo htmlspecialchars($photo['filename']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($photo['title']); ?>" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo htmlspecialchars($photo['title']); ?></h6>
                                        <p class="card-text small text-muted">
                                            <?php echo htmlspecialchars(substr($photo['description'], 0, 60)); ?><?php echo strlen($photo['description']) > 60 ? '...' : ''; ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-<?php 
                                                echo $photo['category'] === 'paysage' ? 'warning' : 
                                                    ($photo['category'] === 'portrait' ? 'success' : 'danger'); 
                                            ?>"><?php echo ucfirst($photo['category']); ?></span>
                                            <strong><?php echo number_format($photo['price'], 2); ?>€</strong>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <?php if ($is_purchased): ?>
                                            <a href="images/<?php echo htmlspecialchars($photo['filename']); ?>" download class="btn btn-success btn-sm w-100">
                                                <i class="bi bi-download"></i> Télécharger
                                            </a>
                                        <?php else: ?>
                                            <form method="POST" class="d-inline w-100">
                                                <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">
                                                <button type="submit" name="buy_photo" class="btn btn-primary btn-sm w-100">
                                                    <i class="bi bi-cart-plus"></i> Acheter
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
