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
<?php if (isset($_SESSION['voyages']) && !empty($_SESSION['voyages'])): ?>
    <h2 class="title">Mes Voyages</h2>
    <table>
        <tr>
            <th>Nom du voyage</th>
            <th>Action</th>
            <th>Etat de payement</th>
        </tr>
        <?php foreach ($_SESSION['voyages'] as $key => $voyage): ?>
            <tr>
                <td><?php echo htmlspecialchars($key); ?></td>
                <td>
                    <a href='trip_recap.php?<?php echo htmlspecialchars($voyage["config"]); ?>'><button>Voir Détails</button></a>
                </td>
                <td><?php if ($voyage['payé']){
                        echo "payé";
                        }
                        else{
                            echo"non payé";
                        }
                     ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Aucun voyage sélectionné.</p>
<?php endif; ?>
</body>
</html>
