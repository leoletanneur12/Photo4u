<?php
require_once 'session.php';

// Traiter la déconnexion
if (isset($_GET['logout'])) {
    logout();
    header('Location: index.php');
    exit();
}
?>
