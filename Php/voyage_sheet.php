<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {
    $voyage["id"] = $_GET["id"];
    if (file_exists("../json/voyagetest.json")) {
        $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
        foreach ($file as $trip) {
            if ($trip["id"] == $voyage["id"]) {
                $voyage["name"] = $trip["name"];
                $voyage["price"] = $trip["price"];
                $voyage["stages"] = $trip["stages"];
                break;
            }
        }
    }
}


include "header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voyage</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/trip_sheet.css">
</head>
<body>

<div class="content">
    <p>
        <?php
        echo "<b>Nom : </b>" . $voyage['name'] . "<br>";
        echo "<b>Prix minimum pour 1 personne: </b>" . $voyage['price'] . "<br>";
        echo "<b>Etapes : </b>";
        foreach ($voyage['stages'] as $stage) {
            echo $stage . " ";

        }
        echo "<br>".file_get_contents("../descript_voyage/".$voyage["id"] . "description.txt");
        ?>
    </p>
    <br>
    <a href="configuration_voyage.php?id=<?php echo $voyage["id"] ?>">Réservation</a>



</div>