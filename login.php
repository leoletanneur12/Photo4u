<?php
require_once 'config.php';
require_once 'session.php';

$error = '';
$success = '';

// Traiter la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['credits'] = isset($user['credits']) ? (float) $user['credits'] : 0.0;

                // Redirection selon le rôle
                switch ($user['role']) {
                    case 'admin':
                        header('Location: admin_dashboard.php');
                        break;
                    case 'photographe':
                        header('Location: photographe_dashboard.php');
                        break;
                    case 'client':
                        header('Location: client_dashboard.php');
                        break;
                    default:
                        header('Location: index.php');
                }
                exit();
            } else {
                $error = 'Identifiants incorrects.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur : ' . $e->getMessage();
        }
    }
}

// Si déjà connecté, rediriger
if (isLoggedIn()) {
    $role = $_SESSION['role'];
    switch ($role) {
        case 'admin':
            header('Location: admin_dashboard.php');
            break;
        case 'photographe':
            header('Location: photographe_dashboard.php');
            break;
        case 'client':
            header('Location: client_dashboard.php');
            break;
        default:
            header('Location: index.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connectez-vous à Photo4u pour accéder à votre espace client ou photographe.">
    <title>Connexion - Photo4u</title>
    <link rel="icon" type="image/png" href="images/logo-photo4u.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="images/logo-photo4u.png" alt="Photo4u Logo" height="80" class="mb-3">
                            <h2 class="h3">Connexion</h2>
                            <p class="text-muted">Accédez à votre espace personnel</p>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="username" name="username" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                            </button>
                        </form>

                        <hr>

                        <div class="text-center">
                            <p class="mb-2">Pas encore de compte ?</p>
                            <a href="register.php" class="btn btn-outline-primary w-100">
                                <i class="bi bi-person-plus me-2"></i>S'inscrire
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="index.php" class="text-decoration-none text-muted">
                                <i class="bi bi-arrow-left me-1"></i>Retour à l'accueil
                            </a>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>Comptes de test :</strong><br>
                                Admin: admin / admin<br>
                                Photo: photo / photo<br>
                                Client: leo / letanneur
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
