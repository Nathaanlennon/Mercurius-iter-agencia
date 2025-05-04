<?php
include "header.php"; // Inclut l'en-tête de la page, souvent pour la navigation, le style, etc.

// Chemin vers le fichier JSON contenant les utilisateurs
$fichier = "../json/utilisateurs.json";

// Si l'utilisateur est déjà connecté, redirige vers la page d'accueil (index.php)
if (isset($info_util['id'])) {
    header("Location: index.php");
    exit;
}

// Chargement des utilisateurs existants à partir du fichier JSON ou initialisation d'un tableau vide
$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

// Variable pour stocker les messages d'erreur ou d'information
$message = "";

// Répertoire pour stocker les demandes d'inscription avant validation
$queue_dir = "../queue";
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true); // Crée le répertoire "queue" si il n'existe pas
}

// Traitement du formulaire d'inscription lors de la soumission (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST["email"]);
    $email_confirm = trim($_POST["email_confirm"]);
    $password = $_POST["password"];
    $mdp_confirm = $_POST["mdp_confirm"];

    // Vérification de la correspondance des emails
    if ($email !== $email_confirm) {
        $message = "email_mismatch"; // Les emails ne correspondent pas
    }
    // Vérification de la correspondance des mots de passe
    elseif ($password !== $mdp_confirm) {
        $message = "password_mismatch"; // Les mots de passe ne correspondent pas
    }
    // Vérification si l'email existe déjà dans la base de données
    elseif (array_filter($utilisateurs, fn($u) => $u["email"] === $email)) {
        $message = "email_exists"; // L'email est déjà utilisé
    } else {
        // Création d'un nouvel utilisateur si les données sont valides
        $nv_id = count($utilisateurs) > 0 ? max(array_column($utilisateurs, "id")) + 1 : 1;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe

        // Nouveau tableau pour l'utilisateur à ajouter
        $nv_util = [
            "id" => $nv_id, // ID unique pour l'utilisateur
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "password" => $hashed_password, // Mot de passe haché pour la sécurité
            "role" => "normal", // Rôle par défaut
        ];

        // Ajout de l'utilisateur au tableau des utilisateurs
        $utilisateurs[] = $nv_util;

        // Sauvegarde de la demande d'inscription dans un fichier JSON dans le répertoire "queue"
        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
        file_put_contents($queue_file, json_encode($nv_util, JSON_PRETTY_PRINT));

        // Redirection vers la page de connexion après l'inscription
        header("Location: connexion.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'inscription</title>
    <link rel="stylesheet" href="../Css/style.css"> <!-- Lien vers la feuille de style -->
    <link rel="stylesheet" href="../Css/auth.css"> <!-- Lien vers la feuille de style d'authentification -->
</head>

<body class="authentification"> <!-- Classe pour l'authentification -->

<div class="content">
    <form method="POST" id="formInscription">
        <table>
            <tr>
                <th colspan="2">Formulaire d'inscription</th> <!-- Titre du formulaire -->
            </tr>
            <tr>
                <td>Nom :</td>
                <td><input type="text" id="nom" name="nom" required></td> <!-- Champ pour le nom -->
            </tr>
            <tr>
                <td>Prénom :</td>
                <td><input type="text" id="prenom" name="prenom" required></td> <!-- Champ pour le prénom -->
            </tr>
            <tr>
                <td>E-mail :</td>
                <td><input type="email" id="email" name="email" required></td> <!-- Champ pour l'email -->
            </tr>
            <tr>
                <td>Confirmation de l'e-mail :</td>
                <td><input type="email" id="email_confirm" name="email_confirm" required></td> <!-- Champ pour confirmer l'email -->
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td>
                    <div class="input-container">
                        <input type="password" id="password" name="password" required>
                        <button type="button" onclick="toggleVisibility('password')">👁</button> <!-- Bouton pour afficher/masquer le mot de passe -->
                    </div>
                </td>
            </tr>
            <tr>
                <td>Confirmation du mot de passe :</td>
                <td>
                    <div class="input-container">
                        <input type="password" id="mdp_confirm" name="mdp_confirm" required>
                        <button type="button" onclick="toggleVisibility('mdp_confirm')">👁</button> <!-- Bouton pour afficher/masquer la confirmation du mot de passe -->
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Inscription</button><br> <!-- Bouton pour soumettre le formulaire -->
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Div cachée pour afficher les messages d'erreur ou de succès -->
<div id="signup-message" data-status="<?= htmlspecialchars($message) ?>" style="display: none;"></div>

<script src="../js/inscription.js"></script> <!-- Script pour gérer l'affichage des messages et l'affichage des mots de passe -->

</body>
</html>
