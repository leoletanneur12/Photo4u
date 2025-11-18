<?php

require_once 'session.php';

// Déconnecter l'utilisateur et rediriger vers la page d'accueil
logout();
header('Location: index.php');
exit();
