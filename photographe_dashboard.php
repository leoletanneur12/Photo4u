<?php
require_once 'config.php';
require_once 'session.php';

requirePhotographe();

$user = getCurrentUser();
$message = '';
$error = '';

// Traiter l'ajout de photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_photo'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $price = floatval($_POST['price']);
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '_' . time() . '.' . $ext;
            $upload_path = 'images/' . $new_filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO photos (photographer_id, title, description, filename, category, price) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$user['id'], $title, $description, $new_filename, $category, $price]);
                    $message = "Photo ajoutée avec succès !";
                } catch(PDOException $e) {
                    $error = "Erreur : " . $e->getMessage();
                    unlink($upload_path);
                }
            } else {
                $error = "Erreur lors de l'upload du fichier.";
            }
        } else {
            $error = "Format de fichier non autorisé.";
        }
    } else {
        $error = "Veuillez sélectionner une photo.";
    }
}

// Traiter la suppression de photo
if (isset($_GET['delete'])) {
    $photo_id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM photos WHERE id = ? AND photographer_id = ?");
        $stmt->execute([$photo_id, $user['id']]);
        $photo = $stmt->fetch();
        
        if ($photo) {
            // Supprimer le fichier
            if (file_exists('images/' . $photo['filename'])) {
                unlink('images/' . $photo['filename']);
            }
            
            // Supprimer de la base de données
            $stmt = $pdo->prepare("DELETE FROM photos WHERE id = ?");
            $stmt->execute([$photo_id]);
            $message = "Photo supprimée avec succès !";
        }
    } catch(PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer toutes les photos du photographe
try {
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE photographer_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user['id']]);
    $photos = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
    $photos = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Photographe - Photo4u</title>
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
                    <i class="bi bi-camera"></i> <?php echo htmlspecialchars($user['username']); ?> (Photographe)
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
        <h1 class="mb-4"><i class="bi bi-camera-fill me-2"></i>Mon Espace Photographe</h1>

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

        <!-- Formulaire d'ajout de photo -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Ajouter une nouvelle photo</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="paysage">Paysage</option>
                                <option value="portrait">Portrait</option>
                                <option value="evenement">Événement</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Prix (€)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="1.50" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="photo" class="form-label">Fichier photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                        </div>
                    </div>
                    <button type="submit" name="add_photo" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>Ajouter la photo
                    </button>
                </form>
            </div>
        </div>

        <!-- Liste des photos -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-images me-2"></i>Mes photos (<?php echo count($photos); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($photos)): ?>
                    <p class="text-muted text-center py-4">Aucune photo ajoutée pour le moment.</p>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($photos as $photo): ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="card h-100">
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
                                        <a href="?delete=<?php echo $photo['id']; ?>" class="btn btn-danger btn-sm w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </a>
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
