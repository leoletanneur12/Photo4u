<?php
require_once 'config.php';
require_once 'session.php';

requirePhotographe();

$user = getCurrentUser();
$message = '';
$error = '';
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'photos';

// Traiter l'ajout de catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    $description = trim($_POST['category_description']);
    $icon = trim($_POST['category_icon']);

    if (!empty($name)) {
        try {
            // Vérifier si la catégorie existe déjà (globalement, pas par photographe)
            $stmt = $pdo->prepare('SELECT id FROM categories WHERE name = ? LIMIT 1');
            $stmt->execute([$name]);

            if ($stmt->fetch()) {
                $error = 'Cette catégorie existe déjà.';
            } else {
                // Créer la catégorie (accessible à tous)
                $stmt = $pdo->prepare('INSERT INTO categories (photographer_id, name, description, icon) VALUES (?, ?, ?, ?)');
                $stmt->execute([null, $name, $description, $icon]);
                $message = 'Catégorie créée avec succès ! Elle est maintenant disponible pour tous les photographes.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur : ' . $e->getMessage();
        }
    } else {
        $error = 'Le nom de la catégorie est requis.';
    }
}

// Traiter la suppression de catégorie (désactivé - les catégories sont partagées)
if (isset($_GET['delete_category'])) {
    $category_id = intval($_GET['delete_category']);
    $error = 'Vous ne pouvez pas supprimer une catégorie car elles sont partagées entre tous les photographes.';
}

// Traiter l'ajout de photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_photo'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Vérifier la taille du fichier (max 5MB)
            if ($_FILES['photo']['size'] <= 5 * 1024 * 1024) {
                $new_filename = uniqid() . '_' . time() . '.' . $ext;
                $upload_path = 'images/' . $new_filename;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                    try {
                        // Récupérer le nom de la catégorie (accessible à tous)
                        $stmt = $pdo->prepare('SELECT name FROM categories WHERE id = ? LIMIT 1');
                        $stmt->execute([$category_id]);
                        $category = $stmt->fetch();

                        if ($category) {
                            $stmt = $pdo->prepare('INSERT INTO photos (photographer_id, title, description, filename, category, category_id, price) VALUES (?, ?, ?, ?, ?, ?, ?)');
                            $stmt->execute([$user['id'], $title, $description, $new_filename, $category['name'], $category_id, $price]);
                            $message = 'Photo ajoutée avec succès !';
                        } else {
                            $error = 'Catégorie invalide.';
                            unlink($upload_path);
                        }
                    } catch (PDOException $e) {
                        $error = 'Erreur : ' . $e->getMessage();
                        unlink($upload_path);
                    }
                } else {
                    $error = "Erreur lors de l'upload du fichier.";
                }
            } else {
                $error = 'Le fichier est trop volumineux (max 5MB).';
            }
        } else {
            $error = 'Format de fichier non autorisé. Formats acceptés : JPG, PNG, GIF, WEBP';
        }
    } else {
        $error = 'Veuillez sélectionner une photo.';
    }
}

// Traiter la suppression de photo
if (isset($_GET['delete_photo'])) {
    $photo_id = intval($_GET['delete_photo']);
    try {
        $stmt = $pdo->prepare('SELECT filename FROM photos WHERE id = ? AND photographer_id = ?');
        $stmt->execute([$photo_id, $user['id']]);
        $photo = $stmt->fetch();

        if ($photo) {
            $stmt = $pdo->prepare('DELETE FROM photos WHERE id = ? AND photographer_id = ?');
            $stmt->execute([$photo_id, $user['id']]);

            if (file_exists('images/' . $photo['filename'])) {
                unlink('images/' . $photo['filename']);
            }
            $message = 'Photo supprimée avec succès !';
        }
    } catch (PDOException $e) {
        $error = 'Erreur : ' . $e->getMessage();
    }
}

// Récupérer toutes les catégories (partagées entre tous les photographes)
try {
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY name');
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    $categories = [];
}

// Récupérer les photos du photographe
try {
    $stmt = $pdo->prepare('
        SELECT p.*, c.name as category_name, c.icon as category_icon,
               (SELECT COUNT(*) FROM purchases WHERE photo_id = p.id) as sales_count
        FROM photos p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.photographer_id = ?
        ORDER BY p.created_at DESC
    ');
    $stmt->execute([$user['id']]);
    $photos = $stmt->fetchAll();
} catch (PDOException $e) {
    $photos = [];
}

// Calculer les statistiques
$total_photos = count($photos);
$total_sales = 0;
$total_revenue = 0;

try {
    $stmt = $pdo->prepare('
        SELECT COUNT(*) as count, SUM(pr.price) as revenue
        FROM purchases pr
        JOIN photos ph ON pr.photo_id = ph.id
        WHERE ph.photographer_id = ?
    ');
    $stmt->execute([$user['id']]);
    $stats = $stmt->fetch();
    $total_sales = $stats['count'] ?? 0;
    $total_revenue = $stats['revenue'] ?? 0;
} catch (PDOException $e) {
    // Ignore
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Espace photographe Photo4u : gérez vos photos et catégories globales.">
    <title>Espace Photographe - Photo4u</title>
        <link rel="icon" type="image/png" href="images/logo-photo4u.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-card.success {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stats-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .photo-grid-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .photo-grid-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .photo-grid-item:hover img {
            transform: scale(1.05);
        }
        .photo-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            padding: 15px;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo-photo4u.png" alt="Photo4u Logo" height="50">
            </a>
            <span class="navbar-text text-white me-auto ms-3">
                <i class="bi bi-camera-fill me-2"></i>Espace Photographe
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
                <h1 class="mb-4"><i class="bi bi-camera-fill me-2"></i>Bienvenue, <?php echo htmlspecialchars($user['username']); ?> !</h1>
                
                <!-- Info pour les photographes -->
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Espace Photographe :</strong> Vous ne voyez que vos propres photos ici. 
                    Pour acheter des photos d'autres photographes, vous devez créer un <a href="register.php" class="alert-link">compte client</a>.
                </div>
                
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
                <div class="stats-card">
                    <h3 class="h4"><i class="bi bi-images"></i> Photos</h3>
                    <h2 class="display-4"><?php echo $total_photos; ?></h2>
                    <p class="mb-0">photos publiées</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card success">
                    <h3 class="h4"><i class="bi bi-cart-check"></i> Ventes</h3>
                    <h2 class="display-4"><?php echo $total_sales; ?></h2>
                    <p class="mb-0">photos vendues</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card info">
                    <h3 class="h4"><i class="bi bi-currency-euro"></i> Revenus</h3>
                    <h2 class="display-4"><?php echo number_format($total_revenue, 2); ?>€</h2>
                    <p class="mb-0">revenus totaux</p>
                </div>
            </div>
        </div>

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php echo $activeTab === 'photos' ? 'active' : ''; ?>" href="?tab=photos">
                    <i class="bi bi-images"></i> Mes Photos (<?php echo $total_photos; ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $activeTab === 'categories' ? 'active' : ''; ?>" href="?tab=categories">
                    <i class="bi bi-folder"></i> Catégories (<?php echo count($categories); ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $activeTab === 'add' ? 'active' : ''; ?>" href="?tab=add">
                    <i class="bi bi-plus-circle"></i> Ajouter une photo
                </a>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content">
            <!-- Onglet Photos -->
            <?php if ($activeTab === 'photos'): ?>
                <div class="row">
                    <?php if (empty($photos)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Vous n'avez pas encore ajouté de photos. 
                                <a href="?tab=add" class="alert-link">Ajoutez votre première photo !</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($photos as $photo): ?>
                            <div class="col-md-4">
                                <div class="photo-grid-item">
                                    <img src="images/<?php echo htmlspecialchars($photo['filename']); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
                                    <div class="photo-overlay">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="badge bg-primary">
                                                    <i class="<?php echo htmlspecialchars($photo['category_icon'] ?? 'bi-folder'); ?>"></i>
                                                    <?php echo htmlspecialchars($photo['category_name'] ?? $photo['category']); ?>
                                                </span>
                                            </div>
                                            <span class="badge bg-success"><?php echo number_format($photo['price'], 2); ?>€</span>
                                        </div>
                                        <h5 class="mb-1"><?php echo htmlspecialchars($photo['title']); ?></h5>
                                        <p class="small mb-2"><?php echo htmlspecialchars(substr($photo['description'], 0, 60)); ?>...</p>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-cart"></i> <?php echo $photo['sales_count']; ?> vente(s)
                                            </span>
                                            <a href="?delete_photo=<?php echo $photo['id']; ?>&tab=photos" 
                                               class="btn btn-sm btn-danger ms-auto"
                                               onclick="return confirm('Supprimer cette photo ?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Onglet Catégories -->
            <?php if ($activeTab === 'categories'): ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Créer une catégorie</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nom de la catégorie *</label>
                                        <input type="text" name="category_name" class="form-control" required 
                                               placeholder="Ex: Nature, Mariage, Architecture...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="category_description" class="form-control" rows="3"
                                                  placeholder="Description de cette catégorie..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Icône Bootstrap Icons</label>
                                        <input type="text" name="category_icon" class="form-control" value="bi-folder"
                                               placeholder="bi-folder, bi-camera, bi-image...">
                                        <small class="text-muted">
                                            Voir les icônes sur <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>
                                        </small>
                                    </div>
                                    <button type="submit" name="add_category" class="btn btn-primary w-100">
                                        <i class="bi bi-plus-circle me-2"></i>Créer la catégorie
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="bi bi-folder me-2"></i>Toutes les catégories (<?php echo count($categories); ?>)</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($categories)): ?>
                                    <p class="text-muted">Aucune catégorie disponible.</p>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($categories as $cat): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="<?php echo htmlspecialchars($cat['icon']); ?> me-2 text-primary"></i>
                                                        <strong><?php echo htmlspecialchars($cat['name']); ?></strong>
                                                    </div>
                                                    <?php if ($cat['description']): ?>
                                                        <small class="text-muted"><?php echo htmlspecialchars($cat['description']); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-share"></i> Partagée
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Onglet Ajouter une photo -->
            <?php if ($activeTab === 'add'): ?>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Ajouter une nouvelle photo</h4>
                            </div>
                            <div class="card-body p-4">
                                <?php if (empty($categories)): ?>
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Vous devez d'abord créer au moins une catégorie. 
                                        <a href="?tab=categories" class="alert-link">Créer une catégorie</a>
                                    </div>
                                <?php else: ?>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label class="form-label">Photo *</label>
                                            <input type="file" name="photo" class="form-control" accept="image/*" required>
                                            <small class="text-muted">Formats acceptés : JPG, PNG, GIF, WEBP - Max 5MB</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Titre *</label>
                                            <input type="text" name="title" class="form-control" required 
                                                   placeholder="Donnez un titre accrocheur à votre photo">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description *</label>
                                            <textarea name="description" class="form-control" rows="4" required
                                                      placeholder="Décrivez votre photo, le contexte, la technique utilisée..."></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Catégorie *</label>
                                                <select name="category_id" class="form-select" required>
                                                    <option value="">-- Choisir une catégorie --</option>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat['id']; ?>">
                                                            <?php echo htmlspecialchars($cat['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Prix (€) *</label>
                                                <input type="number" name="price" class="form-control" 
                                                       min="0.50" max="999.99" step="0.01" value="2.00" required>
                                            </div>
                                        </div>
                                        <button type="submit" name="add_photo" class="btn btn-primary btn-lg w-100">
                                            <i class="bi bi-cloud-upload me-2"></i>Publier la photo
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
