<?php

include "header.php";

$fichier = "utilisateurs.json";

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

$info_util = null;
foreach ($utilisateurs as $utilisateur) {
    if ($utilisateur['id'] == $_SESSION['id']) {
        $info_util = $utilisateur;
        break;
    }
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
<table>
    <tr>
        <th>Nom</th>
        <td><?php echo $info_util['nom']; ?></td>
        <td>
            <a href="modif_profile.php"><button>modifier</button></a>
            <br></td>
    </tr>
    <tr>
        <th>E-mail</th>
        <td><?php echo $info_util['email']; ?></td>
        <td>
            <a href="modif_profile.php"><button>modifier</button></a>
            <br></td>
    </tr>
</table>
</body>
</html>
