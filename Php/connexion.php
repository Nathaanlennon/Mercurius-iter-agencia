<?php

include "header.php";


$fichier = "utilisateurs.json";

// Charger les utilisateurs existants
$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur["email"] === $email) {
            if (password_verify($password, $utilisateur["password"])){
                $_SESSION["id"] = $utilisateur["id"];
                header("Location: index.php");
                exit;
            } else {
                echo "Identifiant incorrect.";
                exit;
            }
        }
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


<body>

<div class="content">
    <form method="POST">
        <table>
            <tr>
                <th colspan="2">Formulaire de connexion</th>
            <tr>
                <td>E-mail :</td>
                <td><label for="email"></label><input type="text" id="email" name="email"/></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><label for="password"></label><input type="text" id="password" name="password"/></td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Connexion</button>
                    <br></td>
            </tr>
        </table>
    </form>
</div>
</body>
