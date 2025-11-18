<?php
/**
 * Script de vérification de l'installation Photo4u
 * Accéder à ce fichier via: http://localhost/Photo4u/check_install.php
 */

$errors = [];
$warnings = [];
$success = [];

// Vérification 1: Version PHP
if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
    $success[] = '✅ PHP version ' . PHP_VERSION . ' (OK)';
} else {
    $errors[] = '❌ PHP version ' . PHP_VERSION . ' (minimum 7.4 requis)';
}

// Vérification 2: Extensions PHP
$required_extensions = ['pdo', 'pdo_mysql', 'session', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        $success[] = "✅ Extension PHP '$ext' installée";
    } else {
        $errors[] = "❌ Extension PHP '$ext' manquante";
    }
}

// Vérification 3: Fichiers de configuration
$required_files = ['config.php', 'session.php', 'sql/database.sql', 'index.php', 'login.php'];
foreach ($required_files as $file) {
    if (file_exists($file)) {
        $success[] = "✅ Fichier '$file' présent";
    } else {
        $errors[] = "❌ Fichier '$file' manquant";
    }
}

// Vérification 4: Dossier images
if (is_dir('images')) {
    if (is_writable('images')) {
        $success[] = "✅ Dossier 'images/' accessible en écriture";
    } else {
        $warnings[] = "⚠️ Dossier 'images/' non accessible en écriture (uploads impossibles)";
    }
} else {
    $errors[] = "❌ Dossier 'images/' manquant";
}

// Vérification 5: Connexion à la base de données
try {
    require_once 'config.php';
    $success[] = '✅ Connexion à la base de données réussie';

    // Vérifier les tables
    $tables = ['users', 'photos', 'purchases'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $success[] = "✅ Table '$table' existe";
        } else {
            $errors[] = "❌ Table '$table' manquante (importer sql/database.sql)";
        }
    }

    // Vérifier les comptes de test
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username IN ('admin', 'photo', 'leo')");
    $count = $stmt->fetchColumn();
    if ($count == 3) {
        $success[] = "✅ Comptes de test créés ($count/3)";
    } else {
        $warnings[] = "⚠️ Comptes de test incomplets ($count/3)";
    }

} catch (Exception $e) {
    $errors[] = '❌ Erreur de connexion BDD: ' . $e->getMessage();
    $warnings[] = "⚠️ Vérifiez config.php et assurez-vous que la BDD 'photo4u' existe";
}

// Affichage
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification Installation - Photo4u</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Vérification de l'installation Photo4u</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($errors)): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Installation réussie !</strong> Tous les tests sont passés.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Erreurs détectées !</strong> Corrigez les erreurs ci-dessous.
                            </div>
                        <?php endif; ?>

                        <!-- Erreurs -->
                        <?php if (!empty($errors)): ?>
                            <h5 class="text-danger mt-4"><i class="bi bi-x-circle-fill me-2"></i>Erreurs</h5>
                            <ul class="list-group mb-3">
                                <?php foreach ($errors as $error): ?>
                                    <li class="list-group-item list-group-item-danger"><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Warnings -->
                        <?php if (!empty($warnings)): ?>
                            <h5 class="text-warning mt-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>Avertissements</h5>
                            <ul class="list-group mb-3">
                                <?php foreach ($warnings as $warning): ?>
                                    <li class="list-group-item list-group-item-warning"><?php echo $warning; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Success -->
                        <h5 class="text-success mt-4"><i class="bi bi-check-circle-fill me-2"></i>Tests réussis</h5>
                        <ul class="list-group mb-3">
                            <?php foreach ($success as $succ): ?>
                                <li class="list-group-item list-group-item-success"><?php echo $succ; ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <hr>

                        <h5 class="mt-4">Prochaines étapes</h5>
                        <ol>
                            <?php if (!empty($errors)): ?>
                                <li class="text-danger">Corriger les erreurs ci-dessus</li>
                            <?php else: ?>
                                <li class="text-success">✅ Installation terminée !</li>
                            <?php endif; ?>
                            <li>Accéder au site: <a href="index.php" class="btn btn-primary btn-sm">index.php</a></li>
                            <li>Se connecter: <a href="login.php" class="btn btn-success btn-sm">login.php</a></li>
                            <li>Tester avec les comptes:
                                <ul>
                                    <li><strong>Admin:</strong> admin / admin</li>
                                    <li><strong>Photographe:</strong> photo / photo</li>
                                    <li><strong>Client:</strong> leo / letanneur</li>
                                </ul>
                            </li>
                        </ol>

                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Documentation:</strong> Consultez <code>INSTALLATION.md</code> et <code>README.md</code> pour plus d'informations.
                        </div>

                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-house-fill me-2"></i>Accéder au site
                            </a>
                            <button onclick="location.reload()" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-clockwise me-2"></i>Re-vérifier
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        <small>Photo4u © 2025 - Système de gestion de photographies</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
