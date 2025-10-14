<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Test de diagnostic Photo4u</h1>";
echo "<hr>";

// Test 1: PHP fonctionne
echo "<h2>✅ Test 1: PHP fonctionne</h2>";
echo "Version PHP: " . PHP_VERSION . "<br>";
echo "<hr>";

// Test 2: Extensions PHP
echo "<h2>Test 2: Extensions PHP</h2>";
$extensions = ['pdo', 'pdo_mysql', 'session'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extension '$ext' : OK<br>";
    } else {
        echo "❌ Extension '$ext' : MANQUANTE<br>";
    }
}
echo "<hr>";

// Test 3: Connexion MySQL
echo "<h2>Test 3: Connexion MySQL</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
    echo "✅ Connexion MySQL réussie<br>";
    
    // Test si la BDD existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'photo4u'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Base de données 'photo4u' existe<br>";
    } else {
        echo "❌ Base de données 'photo4u' n'existe PAS<br>";
        echo "<strong>Action requise :</strong> Créer la BDD et importer database.sql<br>";
    }
} catch(PDOException $e) {
    echo "❌ Erreur MySQL: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// Test 4: Fichiers
echo "<h2>Test 4: Fichiers requis</h2>";
$files = ['config.php', 'session.php', 'index.php', 'login.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe<br>";
    } else {
        echo "❌ $file MANQUANT<br>";
    }
}
echo "<hr>";

// Test 5: Dossier images
echo "<h2>Test 5: Dossier images</h2>";
if (is_dir('images')) {
    if (is_writable('images')) {
        echo "✅ Dossier 'images/' accessible en écriture<br>";
    } else {
        echo "⚠️ Dossier 'images/' non accessible en écriture<br>";
    }
} else {
    echo "❌ Dossier 'images/' manquant<br>";
}
echo "<hr>";

// Test 6: .htaccess
echo "<h2>Test 6: Fichier .htaccess</h2>";
if (file_exists('.htaccess')) {
    echo "✅ .htaccess existe<br>";
    echo "⚠️ Le .htaccess peut causer des erreurs 500 si mal configuré<br>";
    echo "<strong>Solution temporaire :</strong> Renommer .htaccess en .htaccess.bak pour tester<br>";
} else {
    echo "ℹ️ Pas de .htaccess<br>";
}
echo "<hr>";

echo "<h2>🎯 Prochaines étapes</h2>";
echo "<ol>";
echo "<li>Si la base 'photo4u' n'existe pas : <a href='http://localhost/phpmyadmin' target='_blank'>Créer la BDD via phpMyAdmin</a></li>";
echo "<li>Importer le fichier database.sql</li>";
echo "<li>Si l'erreur persiste, renommer temporairement .htaccess</li>";
echo "<li>Tester à nouveau : <a href='index.php'>index.php</a></li>";
echo "</ol>";
?>
