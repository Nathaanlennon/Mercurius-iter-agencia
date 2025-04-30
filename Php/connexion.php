<?php
include "header.php";

$fichier = "../json/utilisateurs.json";

// Redirige si déjà connecté
if (isset($info_util['id'])) {
    header("Location: index.php");
    exit;
}

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur["email"] == $email && password_verify($password, $utilisateur["password"])) {
            if ($utilisateur["role"] === "Banni") {
                echo "    <script>
                            alert('Votre compte a été banni.');
                            window.location.href = 'index.php';
                          </script>";
                exit;
            }
            $_SESSION["id"] = isset($utilisateur["id"]) ? $utilisateur["id"] : [];
            $_SESSION["email"] = isset($utilisateur["email"]) ? $utilisateur["email"] : [];
            $_SESSION["nom"] = isset($utilisateur["nom"]) ? $utilisateur["nom"] : [];
            $_SESSION["role"] = isset($utilisateur["role"]) ? $utilisateur["role"] : [];
            $_SESSION["voyages"] = isset($utilisateur["voyages"]) ? $utilisateur["voyages"] : [];

            setcookie("sans-gluten", $_SESSION["id"], time() + 7200, "/");
            header("Location: index.php");
            exit;
        }
    }

    echo("Identifiant invalide");
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

<script>
    function toggleVisibility() {
        var passwordField = document.getElementById("password");
        var type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;
    }

    document.getElementById("loginForm").addEventListener("submit", function(e) {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === "" || password === "") {
            alert("Tous les champs doivent être remplis.");
            e.preventDefault();
            return;
        }

        if (!emailRegex.test(email)) {
            alert("Adresse email invalide.");
            e.preventDefault();
            return;
        }
    });
</script>

</body>
</html>
