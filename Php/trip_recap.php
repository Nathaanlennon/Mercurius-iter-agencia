<?php
include "header.php";

$info_util = $_SESSION;
if (!isset($info_util['id'])) {
    header("Location: connexion.php");
    exit;
}

if (!($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"]))) {
    header("Location: ../index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récapitulatif voyage</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/trip_sheet.css">
</head>
<body>
<div class="content">
    <div id="recap">

        <?php
        if (file_exists("../json/voyagetest.json") && file_exists("../json/villes_activites.json")) {
            $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
            $file2 = json_decode(file_get_contents("../json/villes_activites.json"), true);
            if (isset($file) && isset($file2)) {
                foreach ($file as $trip) {
                    if ($trip["id"] == $_GET["id"]) {
                        $voyage = $trip;
                        break;
                    }
                }

                echo "<h1>Récapitulatif du voyage</h1>";
                echo "<h2>" . $voyage["name"] . "</h2>";

                $price = 0;
                echo "Avion départ : " . ($price += 100 * $_GET["nb_personnes"]) . "€<br>";
                for ($i = 0; $i < count($voyage["stages"]); $i++) {
                    $ville = [];
                    foreach ($file2 as $stage){
                        if ($stage["name"] == $voyage["stages"][$i]) {
//                            print_r($stage);
                            $ville = [$stage["activities"],$stage["price"]];
                            break;
                        }
                    }
                    echo $voyage["stages"][$i] . "<br>";
                    for ($j = 1; $j < 4; $j++) {// pour les types d'arguments (transport activité etc)
                        if (isset($_GET[$i . $j])) {
                            switch ($j) {
                                case 1:
                                    echo "niveau d'hotel : " . $_GET[$i . $j] . " étoile(s) ";
                                    echo -$price + $price += ((2 ** ($_GET[$i . $j] - 1) * 25 * $_GET["nb_personnes"] * 2));
                                    echo "€";
                                    break;
                                case 2:
                                    echo "activités : ";
                                    foreach ($_GET[$i . $j] as $activity) {
                                        echo $ville[0][$activity-1] . " ";
                                        echo -$price + $price += ($ville[1][$activity-1] * $_GET["nb_personnes"]);
                                        echo " ; ";
                                    }
                                    break;
                                case 3:
                                    echo "transport vers la prochaine étape : ";
                                    switch ($_GET[$i . $j]) {
                                        case 1:
                                            echo "avion ";
                                            echo -$price + ($price += 100 * $_GET["nb_personnes"]) . "€";
                                            break;
                                        case 2:
                                            echo "voiture ";
                                            echo -$price + ($price += 60 * $_GET["nb_personnes"]) . "€";
                                            break;
                                        case 3:
                                            echo "bateau ";
                                            echo -$price + ($price += 80 * $_GET["nb_personnes"]) . "€";
                                            break;
                                        case 4:
                                            echo "train ";
                                            echo -$price + ($price += 50 * $_GET["nb_personnes"]) . "€";
                                            break;

                                    }
                                    break;
                            }
                            echo "<br>";
                        }
                    }
                    echo "<br>";
                }
                echo "Prix total : " . $price . "€";
            }

        } else {
            header("Location: ../index.php");
            exit();
        }

        echo "<br>";
        echo "<br>";
        if ($_SESSION["voyages"][$voyage["name"]]["payé"]) {
            echo "Voyage payé";
        } else {
            echo
                "voyage non payé <br><a href='configuration_voyage.php?" . $_SERVER['QUERY_STRING'] . "'>modification</a> ";

            $nb_user = strlen((string)$_SESSION['id']);
            $nb_trip = strlen((string)$voyage['id']);
            echo "<form action='payement.php' method='post'>
    <input type='hidden' value='" . $price . "' name='price'>
    <input type='hidden' value='" . (0) . $nb_user . $_SESSION["id"] . $nb_trip . $voyage["id"] . implode('', array_map(function () { //0 means it's a simple transaction
                    return dechex(rand(0, 15));
                }, range(1, 7 - $nb_trip - $nb_user))) . "' name='id'>
    <button type='submit'>Payement</button>
</form>
";
        }


        ?>
    </div>
</div>
</body>


