<?php
include "header.php";

$info_util = $_SESSION;
if (!isset($info_util['id'])) {
    header("Location: connexion.php");
    exit;
}
$queue_dir = "../queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nv_info = [
        'id' => $_SESSION['id'],
        'nom' => isset($_POST['nom']) ? $_POST['nom'] : $_SESSION['nom'],
        'email' => isset($_POST['email']) ? $_POST['email'] : $_SESSION['email']
    ];

    if (!filter_var($nv_info['email'], FILTER_VALIDATE_EMAIL)) {
        echo("Email invalide;");
        exit;
    }

    $_SESSION["email"] = $nv_info["email"];
    $_SESSION["nom"] = $nv_info["nom"];


    $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
    file_put_contents($queue_file, json_encode($nv_info, JSON_PRETTY_PRINT));
    header("Location: profile.php");
    exit;
}

?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'inscription</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/profile.css">

</head>

<body>
<h1 class="title">Profil</h1>
<form method="POST">
    <table>
        <tr>
            <th>Nom</th>
            <td><label for="nom"></label><input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($_SESSION['nom']); ?>" /></td>
            <td>
                <a href="profile.php"><button type="button">annuler</button></a>
                <button type="submit">confirmer</button>
                <br></td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><label for="email"></label><input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" /></td>
            <td>
                <a href="profile.php"><button type="button">annuler</button></a>
                <button type="submit">confirmer</button>
                <br></td>
        </tr>
    </table>
</form>
</body>
</html>

