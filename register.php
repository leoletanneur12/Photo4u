<?php
require_once 'config.php';
require_once 'session.php';

$error = '';
$success = '';

// Traiter l'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) && in_array($_POST['role'], ['client', 'photographe']) ? $_POST['role'] : 'client';
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } else {
        try {
            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $error = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
            } else {
                // Créer le compte avec le rôle choisi
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, $role]);
                
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            }
        } catch(PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Photo4u</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="images/logo-photo4u.svg" alt="Photo4u Logo" height="80" class="mb-3">
                            <h2 class="h3">Inscription</h2>
                            <p class="text-muted">Créez votre compte client</p>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                                <div class="mt-2">
                                    <a href="login.php" class="btn btn-sm btn-success">Se connecter maintenant</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-person-badge me-2"></i>Je m'inscris en tant que</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="role" id="role_client" value="client" checked>
                                    <label class="btn btn-outline-primary" for="role_client">
                                        <i class="bi bi-bag-heart"></i> Acheteur / Client
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="role" id="role_photographe" value="photographe">
                                    <label class="btn btn-outline-success" for="role_photographe">
                                        <i class="bi bi-camera"></i> Photographe
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <strong>Acheteur :</strong> Pour acheter et télécharger des photos<br>
                                    <strong>Photographe :</strong> Pour vendre vos propres photos
                                </small>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="username" name="username" required 
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                <small class="text-muted">Minimum 6 caractères</small>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-person-plus me-2"></i>Créer mon compte
                            </button>
                        </form>

                        <hr>

                        <div class="text-center">
                            <p class="mb-2">Vous avez déjà un compte ?</p>
                            <a href="login.php" class="btn btn-outline-primary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="index.php" class="text-decoration-none text-muted">
                                <i class="bi bi-arrow-left me-1"></i>Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
