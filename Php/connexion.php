<?php
include "header.php";

$fichier = "../json/utilisateurs.json";

if (isset($info_util['id'])) {
    header("Location: index.php");
    exit;
}

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

$message = ""; // <-- Pour stocker les messages à transmettre au JS

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur["email"] == $email && password_verify($password, $utilisateur["password"])) {
            if ($utilisateur["role"] === "Banni") {
                $message = "banni";
                break;
            }
            $_SESSION["id"] = $utilisateur["id"] ?? [];
            $_SESSION["email"] = $utilisateur["email"] ?? [];
            $_SESSION["nom"] = $utilisateur["nom"] ?? [];
            $_SESSION["role"] = $utilisateur["role"] ?? [];
            $_SESSION["voyages"] = $utilisateur["voyages"] ?? [];

            setcookie("sans-gluten", $_SESSION["id"], time() + 7200, "/");
            header("Location: index.php");
            exit;
        }
    }

    if (!$message) {
        $message = "invalide";
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
<script src="connexion.js"></script>

</body>
</html>
