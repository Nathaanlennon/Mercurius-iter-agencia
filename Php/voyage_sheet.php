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
} else {
    header("Location: research.php");
    exit();
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
        echo "Nom : " . $voyage['name'] . "<br>";
        echo "Prix minimum pour 1 personne: " . $voyage['price'] . "<br>";
        echo "Etapes : ";
        foreach ($voyage['stages'] as $stage) {
            echo $stage . " ";
        }
        ?>
    </p>
    <br>
        <a href="configuration_voyage.php?id=<?php echo $voyage["id"] ?>">Réservation</a>



</div>