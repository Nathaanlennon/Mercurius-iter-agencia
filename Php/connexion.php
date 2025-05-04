<?php
include "header.php"; // Inclusion de l'en-tête, où probablement la session est démarrée

$fichier = "../json/utilisateurs.json"; // Définition du fichier JSON contenant les utilisateurs

// Vérifie si un utilisateur est déjà connecté (si un id existe dans la session, redirection vers la page d'accueil)
if (isset($info_util['id'])) {
    header("Location: index.php"); // Redirection vers la page d'accueil
    exit;
}

// Lecture des utilisateurs depuis le fichier JSON
$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

$message = ""; // Variable pour stocker les messages qui seront envoyés au JavaScript

// Traitement de la soumission du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]); // Nettoyage de l'email
    $password = $_POST["password"]; // Récupération du mot de passe

    // Vérification des informations de connexion dans le fichier des utilisateurs
    foreach ($utilisateurs as $utilisateur) {
        // Vérification de l'email et du mot de passe de l'utilisateur
        if ($utilisateur["email"] == $email && password_verify($password, $utilisateur["password"])) {
            // Si l'utilisateur est banni, on retourne un message
            if ($utilisateur["role"] === "Banni") {
                $message = "banni"; // Message pour un utilisateur banni
                break;
            }
            // Si l'utilisateur est trouvé et valide, on initialise la session
            $_SESSION["id"] = $utilisateur["id"] ?? [];
            $_SESSION["email"] = $utilisateur["email"] ?? [];
            $_SESSION["nom"] = $utilisateur["nom"] ?? [];
            $_SESSION["role"] = $utilisateur["role"] ?? [];
            $_SESSION["voyages"] = $utilisateur["voyages"] ?? [];

            // Création d'un cookie pour l'utilisateur avec une durée de 2 heures
            setcookie("sans-gluten", $_SESSION["id"], time() + 7200, "/");

            // Redirection vers la page d'accueil après une connexion réussie
            header("Location: index.php");
            exit;
        }
    }

    // Si aucune correspondance n'a été trouvée, le message est "invalide"
    if (!$message) {
        $message = "invalide"; // Message d'erreur en cas de mauvais identifiant
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../Css/style.css"> <!-- Feuille de style principale -->
    <link rel="stylesheet" href="../Css/auth.css"> <!-- Feuille de style spécifique à l'authentification -->
</head>
<body class="authentification"> <!-- Classe "authentification" pour la page de connexion -->

<div class="content">
    <!-- Formulaire de connexion -->
    <form method="POST" id="loginForm">
        <table>
            <tr>
                <th colspan="2">Formulaire de connexion</th> <!-- Titre du formulaire -->
            </tr>
            <tr>
                <td>E-mail :</td> <!-- Champ pour l'email -->
                <td><label for="email"></label><input type="text" id="email" name="email"></td>
            </tr>
            <tr>
                <td>Mot de passe :</td> <!-- Champ pour le mot de passe -->
                <td><label for="password"></label>
                    <div class="input-container">
                        <input type="password" id="password" name="password"> <!-- Champ de mot de passe -->
                        <button type="button" onclick="toggleVisibility()">👁</button> <!-- Bouton pour afficher/masquer le mot de passe -->
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Connexion</button><br> <!-- Bouton de soumission -->
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Div cachée avec le statut du message (sera utilisé par le JS pour afficher les erreurs) -->
<div id="login-message" data-status="<?= htmlspecialchars($message) ?>" style="display: none;"></div>

<!-- Inclusion du script JavaScript pour gérer la logique de connexion -->
<script src="../js/connexion.js"></script>

</body>
</html>
