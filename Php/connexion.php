<?php

include "header.php";


$fichier = "../json/utilisateurs.json";

if (isset($info_util['id'])) {
    header("Location: index.php");
    exit;
}



$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo("Email invalide;");
    }
    else{
        foreach ($utilisateurs as $utilisateur ){
            if ($utilisateur["email"] == $email && password_verify($password, $utilisateur["password"])) {
                if ($utilisateur["role"] === "Banni") {
                    echo "Vous avez été banni";
                    exit;
                }
                $_SESSION["id"] = (isset($utilisateur["id"]) ? $utilisateur["id"] : []);
                $_SESSION["email"] = (isset($utilisateur["email"]) ? $utilisateur["email"] : []);
                $_SESSION["nom"] = (isset($utilisateur["nom"]) ? $utilisateur["nom"] : []);
                $_SESSION["role"] = (isset($utilisateur["role"]) ? $utilisateur["role"] : []);
                $_SESSION["voyages"] = (isset($utilisateur["voyages"]) ? $utilisateur["voyages"] : []);

                setcookie("sans-gluten", $_SESSION["id"], time() + 7200, "/");
                header("Location: index.php");
                exit;
            }
        }
        echo("Identifiant invalide");
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
                <td><label for="email"></label><input type="text" id="email" name="email" required></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><label for="password"></label><input type="text" id="password" name="password" required></td>
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
