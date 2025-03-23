<?php

include "header.php";
$info_util = $_SESSION;
if (!isset($info_util['id'])) {
    header("Location: connexion.php");
    exit;
}

$fichier = "../json/utilisateurs.json";

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];


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
<?php
if(isset($_SESSION['voyages'])) {
    foreach ($_SESSION['voyages'] as $key => $value) {
        echo "<a href='trip_recap.php?".$value["config"]."'>".$key."</a>";
    }
}


?>
</body>
</html>
