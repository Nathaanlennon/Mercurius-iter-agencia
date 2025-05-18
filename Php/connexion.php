<?php
include "header.php";

$fichier = "../json/utilisateurs.json";//chemin pour le json

if (isset($_SESSION['id'])) {
    header("Location: index.php");//si l'utilisateur est déja connecté le redirige
    exit;
}

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];//chargement des utilisateurs, si e fichier n'existe pas, on obtient un tableau vide

$message = ""; //Pour stocker les messages à transmettre au JS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    foreach ($utilisateurs as $utilisateur) {//vérifie les informations données avec chaque utilisateur présent dans le json
        if ($utilisateur["email"] == $email && password_verify($password, $utilisateur["password"])) {
            if ($utilisateur["role"] === "Banni") {
                $message = "banni";//si utilisateur banni, on envoie message banni au JS
                break;
            }
            //Enregistrement des informations dans la session
            $_SESSION["id"] = $utilisateur["id"] ?? [];
            $_SESSION["email"] = $utilisateur["email"] ?? [];
            $_SESSION["nom"] = $utilisateur["nom"] ?? [];
            $_SESSION["role"] = $utilisateur["role"] ?? [];
            $_SESSION["voyages"] = $utilisateur["voyages"] ?? [];

            setcookie("sans-gluten", $_SESSION["id"], time() + 7200, "/");//création du cookie de connexion disponible 2h
            header("Location: index.php");
            exit;
        }
    }

    if (!$message) {
        $message = "invalide";//si aucun utilisateur ne correspond envoi message indisponible au JS
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/auth.css">
</head>
<body class="authentification">


<div class="content">
    <form method="POST" id="loginForm">
        <table>
            <tr>
                <th colspan="2">Formulaire de connexion</th>
            </tr>
            <tr>
                <td>E-mail :</td>
                <td><label for="email"></label><input type="text" id="email" name="email"></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><label for="password"></label>
                    <div class="input-container">
                        <input type="password" id="password" name="password">
                        <button type="button" onclick="toggleVisibility()">👁</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Connexion</button><br>
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="login-message" data-status="<?= htmlspecialchars($message) ?>" style="display: none;"></div>
<script src="../js/connexion.js"></script>

</body>
</html>
