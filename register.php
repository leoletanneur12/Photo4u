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
        $error = 'Veuillez remplir tous les champs.';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide.';
    } else {
        try {
            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $email]);

            if ($stmt->fetch()) {
                $error = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
            } else {
                // Créer le compte avec le rôle choisi
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
                $stmt->execute([$username, $email, $hashed_password, $role]);

                $success = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Créez un compte Photo4u pour acheter des photos en crédits ou gérer votre portfolio de photographe.">
    <title>Inscription - Photo4u</title>
    <link rel="icon" type="image/png" href="images/logo-photo4u.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .register-sidebar {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .register-sidebar h2 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .register-sidebar .feature {
            display: flex;
            align-items: start;
            margin-bottom: 15px;
        }
        .register-sidebar .feature i {
            font-size: 1.5rem;
            margin-right: 15px;
            margin-top: 3px;
        }
        .role-card {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .role-card:hover {
            border-color: var(--primary-color);
            background: #fff5f5;
            transform: translateY(-3px);
        }
        .role-card.selected {
            border-color: var(--primary-color);
            background: #fff5f5;
            box-shadow: 0 5px 15px rgba(215, 35, 35, 0.3);
        }
        .role-card i {
            font-size: 3rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .role-card input[type="radio"] {
            display: none;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(215, 35, 35, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="register-container">
                    <div class="row g-0">
                        <!-- Sidebar gauche avec avantages -->
                        <div class="col-md-5 register-sidebar d-none d-md-flex">
                            <div>
                                <img src="images/logo-photo4u.png" alt="Photo4u" style="max-width: 150px; margin-bottom: 30px;">
                                <h2>Rejoignez Photo4u</h2>
                                <p class="mb-4">La plateforme qui connecte photographes et passionnés de photographie</p>
                                
                                <div class="feature">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>
                                        <strong>Pour les Clients</strong>
                                        <p class="small mb-0">Achetez des photos uniques de qualité professionnelle</p>
                                    </div>
                                </div>
                                
                                <div class="feature">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>
                                        <strong>Pour les Photographes</strong>
                                        <p class="small mb-0">Vendez vos créations et gérez votre portfolio</p>
                                    </div>
                                </div>
                                
                                <div class="feature">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>
                                        <strong>Catégories personnalisées</strong>
                                        <p class="small mb-0">Organisez et trouvez facilement ce que vous cherchez</p>
                                    </div>
                                </div>
                                
                                <div class="feature">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>
                                        <strong>Paiements sécurisés</strong>
                                        <p class="small mb-0">Transactions protégées et téléchargements instantanés</p>
                                    </div>
                                </div>
                                
                                <div class="feature">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>
                                        <strong>Support 24/7</strong>
                                        <p class="small mb-0">Notre équipe est là pour vous accompagner</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire d'inscription -->
                        <div class="col-md-7">
                            <div class="p-5">
                                <div class="text-center mb-4">
                                    <h3 class="mb-2">Créer un compte</h3>
                                    <p class="text-muted">Rejoignez notre communauté en quelques clics</p>
                                </div>

                                <?php if ($error): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($success): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                                        <div class="mt-2">
                                            <a href="login.php" class="btn btn-sm btn-success">Se connecter maintenant</a>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action="">
                                    <!-- Choix du rôle -->
                                    <div class="mb-4">
                                        <label class="form-label mb-3"><strong>Je souhaite :</strong></label>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="role-card" for="role_client">
                                                    <input type="radio" name="role" id="role_client" value="client" checked>
                                                    <i class="bi bi-bag-heart"></i>
                                                    <h5>Acheter</h5>
                                                    <p class="small text-muted mb-0">Je veux acheter des photos</p>
                                                </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="role-card" for="role_photographe">
                                                    <input type="radio" name="role" id="role_photographe" value="photographe">
                                                    <i class="bi bi-camera"></i>
                                                    <h5>Vendre</h5>
                                                    <p class="small text-muted mb-0">Je veux vendre mes photos</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Nom d'utilisateur</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control" id="username" name="username" required 
                                                   placeholder="Votre nom d'utilisateur"
                                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Adresse email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email" required
                                                   placeholder="votre@email.com"
                                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                            <input type="password" class="form-control" id="password" name="password" required minlength="6"
                                                   placeholder="Minimum 6 caractères">
                                        </div>
                                        <small class="text-muted">Utilisez au moins 6 caractères avec des lettres et chiffres</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                                                   placeholder="Retapez votre mot de passe">
                                        </div>
                                    </div>

                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label small" for="terms">
                                            J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a>
                                        </label>
                                    </div>

                                    <button type="submit" name="register" class="btn btn-primary w-100 mb-3">
                                        <i class="bi bi-person-plus me-2"></i>Créer mon compte
                                    </button>
                                </form>

                                <hr class="my-4">

                                <div class="text-center">
                                    <p class="mb-2">Vous avez déjà un compte ?</p>
                                    <a href="login.php" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                    </a>
                                </div>

                                <div class="text-center mt-3">
                                    <a href="index.php" class="text-decoration-none text-muted small">
                                        <i class="bi bi-arrow-left me-1"></i>Retour à l'accueil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour gérer la sélection visuelle des cartes de rôle
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
        
        // Sélectionner la carte client par défaut
        document.querySelector('#role_client').closest('.role-card').classList.add('selected');
    </script>
</body>
</html>
