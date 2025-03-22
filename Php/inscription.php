<?php
include "header.php";
$fichier = "../json/utilisateurs.json";

if (isset($info_util['id'])) {
    header("Location: index.php");
    exit;
}

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

$queue_dir = "queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir("../json/$queue_dir", 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST["email"]);
    $email_confirm = trim($_POST["email_confirm"]);
    $password = $_POST["password"];
    $mdp_confirm = $_POST["mdp_confirm"];

       if ($email !== $email_confirm) {
           echo "Les emails ne correspondent pas !";
           exit;
       }


    if ($password !== $mdp_confirm) {
        echo "Les mots de passe ne correspondent pas !";
        exit;
    }

    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur["email"] === $email ) {
            exit("Erreur : cet email est déjà utilisé.");
        }
    }



    $nv_id = count($utilisateurs) > 0 ? max(array_column($utilisateurs, "id")) + 1 : 1;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);



    $nv_util = [
        "id" => "0",
        "nom" => $nom,
        "prenom" => $prenom,
        "email" => $email,
        "password" => $hashed_password,
        "role" =>  "normal",
    ];
    $utilisateurs[] = $nv_util;

    $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
    file_put_contents($queue_file, json_encode($nv_util, JSON_PRETTY_PRINT));
    header("Location: connexion.php");
    exit;
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

<body>
<div class="content">
    <form method="POST">
        <table>
            <tr>
                <th colspan="2">Formulaire d'inscription</th>
            <tr>
                <td>Nom :</td>
                <td><label for="nom"></label><input type="text" id="nom" name="nom"/></td>
            </tr>
            <tr>
                <td>Prenom :</td>
                <td><label for="prenom"></label><input type="text" id="prenom" name="prenom"/></td>
            </tr>
            <tr>
                <td>E-mail :</td>
                <td><label for="email"></label><input type="text" id="email" name="email"/></td>
            </tr>
            <tr>
                <td>confirmation de l'e-mail :</td>
                <td><label for="email_confirm"></label><input type="text" id="email_confirm" name="email_confirm"/></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><label for="password"></label><input type="text" id="password" name="password"/></td>
            </tr>
            <tr>
                <td> Confirmation du mot de passe :</td>
                <td><label for="mdp_confirm"></label><input type="text" id="mdp_confirm" name="mdp_confirm"/></td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Inscription</button>
                    <br></td>
            </tr>
        </table>
    </form>

</div>
</body>
