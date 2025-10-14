<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Obtenir l'utilisateur actuel
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'email' => $_SESSION['email'] ?? ''
        ];
    }
    return null;
}

// Vérifier le rôle de l'utilisateur
function hasRole($role) {
    return isLoggedIn() && $_SESSION['role'] === $role;
}

// Vérifier si l'utilisateur est admin
function isAdmin() {
    return hasRole('admin');
}

// Vérifier si l'utilisateur est photographe
function isPhotographe() {
    return hasRole('photographe');
}

// Vérifier si l'utilisateur est client
function isClient() {
    return hasRole('client');
}

// Rediriger si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Rediriger si non admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

// Rediriger si non photographe
function requirePhotographe() {
    requireLogin();
    if (!isPhotographe() && !isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

// Déconnexion
function logout() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    session_destroy();
}
?>
