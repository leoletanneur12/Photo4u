<?php

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'photo4u');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Définir le fuseau horaire
date_default_timezone_set('Europe/Paris');
