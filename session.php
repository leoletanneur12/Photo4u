<?php

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Obtenir l'utilisateur actuel
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    // Always expose known session values
    $user = [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role'],
        'email' => $_SESSION['email'] ?? ''
    ];

    // Inject credits if already stockés en session
    if (array_key_exists('credits', $_SESSION)) {
        $user['credits'] = (float) $_SESSION['credits'];
        return $user;
    }

    // Crédit manquant : tenter une récupération silencieuse depuis la BDD
    global $pdo;

    if (!isset($pdo)) {
        $configPath = __DIR__ . '/config.php';
        if (file_exists($configPath)) {
            require_once $configPath;
        }
    }

    if (isset($pdo)) {
        try {
            $stmt = $pdo->prepare('SELECT email, credits FROM users WHERE id = ? LIMIT 1');
            $stmt->execute([$user['id']]);
            if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $user['email'] = $row['email'];
                $_SESSION['email'] = $row['email'];
                $user['credits'] = (float) $row['credits'];
                $_SESSION['credits'] = $user['credits'];
            } else {
                $user['credits'] = 0.0;
                $_SESSION['credits'] = 0.0;
            }
        } catch (\PDOException $e) {
            $user['credits'] = 0.0;
            $_SESSION['credits'] = 0.0;
        }
    } else {
        $user['credits'] = 0.0;
        $_SESSION['credits'] = 0.0;
    }

    return $user;
}

// Vérifier le rôle de l'utilisateur
function hasRole($role)
{
    return isLoggedIn() && $_SESSION['role'] === $role;
}

// Vérifier si l'utilisateur est admin
function isAdmin()
{
    return hasRole('admin');
}

// Vérifier si l'utilisateur est photographe
function isPhotographe()
{
    return hasRole('photographe');
}

// Vérifier si l'utilisateur est client
function isClient()
{
    return hasRole('client');
}

// Rediriger si non connecté
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Rediriger si non admin
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

// Rediriger si non photographe
function requirePhotographe()
{
    requireLogin();
    if (!isPhotographe() && !isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

// Déconnexion
function logout()
{
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
}
