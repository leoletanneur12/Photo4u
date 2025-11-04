<?php
require_once 'config.php';
require_once 'session.php';

requireAdmin();

$user = getCurrentUser();
$message = '';
$error = '';

// Traiter la suppression d'utilisateur
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    if ($user_id != $user['id']) { // Ne pas pouvoir se supprimer soi-même
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $message = "Utilisateur supprimé avec succès !";
        } catch(PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    } else {
        $error = "Vous ne pouvez pas supprimer votre propre compte.";
    }
}

// Traiter la suppression de photo
if (isset($_GET['delete_photo'])) {
    $photo_id = intval($_GET['delete_photo']);
    try {
        $stmt = $pdo->prepare("SELECT filename FROM photos WHERE id = ?");
        $stmt->execute([$photo_id]);
        $photo = $stmt->fetch();
        
        if ($photo && file_exists('images/' . $photo['filename'])) {
            unlink('images/' . $photo['filename']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM photos WHERE id = ?");
        $stmt->execute([$photo_id]);
        $message = "Photo supprimée avec succès !";
    } catch(PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer les statistiques
try {
    $stats = [];
    $stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $stats['total_photos'] = $pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
    $stats['total_purchases'] = $pdo->query("SELECT COUNT(*) FROM purchases")->fetchColumn();
    $stats['total_revenue'] = $pdo->query("SELECT SUM(price) FROM purchases")->fetchColumn() ?? 0;
    
    // Récupérer tous les utilisateurs
    $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    
    // Récupérer toutes les photos
    $photos = $pdo->query("
        SELECT ph.*, u.username as photographer_name
        FROM photos ph
        JOIN users u ON ph.photographer_id = u.id
        ORDER BY ph.created_at DESC
    ")->fetchAll();
    
    // Récupérer tous les achats
    $purchases = $pdo->query("
        SELECT p.*, u.username as client_name, ph.title as photo_title, ph.filename
        FROM purchases p
        JOIN users u ON p.client_id = u.id
        JOIN photos ph ON p.photo_id = ph.id
        ORDER BY p.purchase_date DESC
        LIMIT 10
    ")->fetchAll();
    
} catch(PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
    $users = [];
    $photos = [];
    $purchases = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Photo4u</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo-photo4u.png" alt="Photo4u Logo" height="40">
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white">
                    <i class="bi bi-shield-fill-check"></i> <?php echo htmlspecialchars($user['username']); ?> (Admin)
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

    <div class="container-fluid my-4">
        <h1 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard Administrateur</h1>

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

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase">Utilisateurs</h6>
                                <h2 class="mb-0"><?php echo $stats['total_users']; ?></h2>
                            </div>
                            <i class="bi bi-people-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase">Photos</h6>
                                <h2 class="mb-0"><?php echo $stats['total_photos']; ?></h2>
                            </div>
                            <i class="bi bi-images" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase">Achats</h6>
                                <h2 class="mb-0"><?php echo $stats['total_purchases']; ?></h2>
                            </div>
                            <i class="bi bi-cart-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase">Revenus</h6>
                                <h2 class="mb-0"><?php echo number_format($stats['total_revenue'], 2); ?>€</h2>
                            </div>
                            <i class="bi bi-currency-euro" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button">
                    <i class="bi bi-people"></i> Utilisateurs (<?php echo count($users); ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos" type="button">
                    <i class="bi bi-images"></i> Photos (<?php echo count($photos); ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button">
                    <i class="bi bi-cart"></i> Derniers achats
                </button>
            </li>
        </ul>

        <div class="tab-content border border-top-0 p-4" id="adminTabsContent">
            <!-- Utilisateurs -->
            <div class="tab-pane fade show active" id="users" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo $u['id']; ?></td>
                                    <td><?php echo htmlspecialchars($u['username']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $u['role'] === 'admin' ? 'danger' : 
                                                ($u['role'] === 'photographe' ? 'warning' : 'primary'); 
                                        ?>"><?php echo ucfirst($u['role']); ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($u['created_at'])); ?></td>
                                    <td>
                                        <?php if ($u['id'] != $user['id']): ?>
                                            <a href="?delete_user=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet utilisateur ?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Vous-même</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Photos -->
            <div class="tab-pane fade" id="photos" role="tabpanel">
                <div class="row g-3">
                    <?php foreach ($photos as $photo): ?>
                        <div class="col-md-3 col-lg-2">
                            <div class="card">
                                <img src="images/<?php echo htmlspecialchars($photo['filename']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($photo['title']); ?>" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title small mb-1"><?php echo htmlspecialchars($photo['title']); ?></h6>
                                    <p class="card-text small text-muted mb-1">Par: <?php echo htmlspecialchars($photo['photographer_name']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small><?php echo number_format($photo['price'], 2); ?>€</small>
                                        <a href="?delete_photo=<?php echo $photo['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette photo ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Achats -->
            <div class="tab-pane fade" id="purchases" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Photo</th>
                                <th>Prix</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchases as $purchase): ?>
                                <tr>
                                    <td><?php echo $purchase['id']; ?></td>
                                    <td><?php echo htmlspecialchars($purchase['client_name']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="images/<?php echo htmlspecialchars($purchase['filename']); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;" class="me-2 rounded">
                                            <?php echo htmlspecialchars($purchase['photo_title']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($purchase['price'], 2); ?>€</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($purchase['purchase_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
