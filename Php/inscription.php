<?php
include "header.php";
$fichier = "../json/utilisateurs.json";

if (isset($info_util['id'])) {
    header("Location: index.php");
    exit;
}

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];
$message = "";

$queue_dir = "../queue";
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST["email"]);
    $email_confirm = trim($_POST["email_confirm"]);
    $password = $_POST["password"];
    $mdp_confirm = $_POST["mdp_confirm"];

    if ($email !== $email_confirm) {
        $message = "email_mismatch";
    } elseif ($password !== $mdp_confirm) {
        $message = "password_mismatch";
    } elseif (array_filter($utilisateurs, fn($u) => $u["email"] === $email)) {
        $message = "email_exists";
    } else {
        $nv_id = count($utilisateurs) > 0 ? max(array_column($utilisateurs, "id")) + 1 : 1;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $nv_util = [
            "id" => "0",
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "password" => $hashed_password,
            "role" => "normal",
        ];

        $utilisateurs[] = $nv_util;
        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
        file_put_contents($queue_file, json_encode($nv_util, JSON_PRETTY_PRINT));

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
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/auth.css">
</head>

<body class="authentification">
<div class="content">
    <form method="POST" id="formInscription">
        <table>
            <tr>
                <th colspan="2">Formulaire d'inscription</th>
            </tr>
            <tr>
                <td>Nom :</td>
                <td><input type="text" id="nom" name="nom"></td>
            </tr>
            <tr>
                <td>Prénom :</td>
                <td><input type="text" id="prenom" name="prenom"></td>
            </tr>
            <tr>
                <td>E-mail :</td>
                <td><input type="text" id="email" name="email"></td>
            </tr>
            <tr>
                <td>Confirmation de l'e-mail :</td>
                <td><input type="text" id="email_confirm" name="email_confirm"></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td>
                    <div class="input-container">
                        <input type="password" id="password" name="password">
                        <button type="button" onclick="toggleVisibility('password')">👁</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Confirmation du mot de passe :</td>
                <td>
                    <div class="input-container">
                        <input type="password" id="mdp_confirm" name="mdp_confirm">
                        <button type="button" onclick="toggleVisibility('mdp_confirm')">👁</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Inscription</button><br>
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="signup-message" data-status="<?= htmlspecialchars($message) ?>" style="display: none;"></div>
<script src="../js/inscription.js"></script>

</body>
</html>
