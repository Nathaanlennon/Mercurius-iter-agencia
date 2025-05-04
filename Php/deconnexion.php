<?php
session_start(); // Démarre une nouvelle session ou reprend la session existante

// Supprime le cookie "sans-gluten" en définissant sa durée d'expiration à une heure dans le passé (3600 secondes = 1 heure)
setcookie("sans-gluten", $_SESSION["id"], time() - 3600, "/");

// Supprime toutes les variables de session
session_unset();

// Détruit la session et efface toutes les données liées à la session
session_destroy();

// Redirige l'utilisateur vers la page d'accueil après la déconnexion
header("Location: index.php");

// Arrête l'exécution du script après la redirection
exit;
