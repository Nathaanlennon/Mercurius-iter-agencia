<?php

if (!($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"]))) {
    header("Location: ../index.php");
    exit();

} else {
    include "header.php";

}

if (file_exists("../json/voyagetest.json")) {
    $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
    if (isset($file)) {
        foreach ($file as $trip) {
            if ($trip["id"] == $_GET["id"]) {
                $voyage = $trip;
                break;
            }
        }
        echo "<h1>Récapitulatif du voyage</h1>";
        echo "<h2>" . $voyage["name"] . "</h2>";

        $price = 0;
        echo "Avion départ : ". ($price+=100  * $_GET["nb_personnes"]) . "€<br>";
        for ($i = 0; $i < count($voyage["stages"]); $i++) {
            echo $voyage["stages"][$i] . "<br>";
            for ($j = 1; $j < 4; $j++) {
                if (isset($_GET[$i . $j])) {
                    switch ($j) {
                        case 1:
                            echo "niveau d'hotel : " . $_GET[$i . $j] . " étoile(s) ";
                            echo -$price + $price += ((2 ** $_GET[$i . $j] - 1) * 25 * $_GET["nb_personnes"] * ($voyage["duration"]) / count($voyage["stages"]));
                            echo "€";
                            break;
                        case 2:
                            echo "activités : ";
                            foreach ($_GET[$i . $j] as $activity) {
                                switch ($activity) {
                                    case 1:
                                        echo "musée ";
                                        echo -$price + ($price += 10 * $_GET["nb_personnes"]) . "€";
                                        break;
                                    case 2:
                                        echo "visites de ruines ";
                                        echo -$price + ($price += 20 * $_GET["nb_personnes"]) . "€";
                                        break;
                                    case 3:
                                        echo "spectacle ";
                                        echo -$price + ($price += 30 * $_GET["nb_personnes"]) . "€";
                                        break;
                                    case 4:
                                        echo "plage ";
                                        break;
                                }
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
                                    echo "train ";
                                    echo -$price + ($price += 50 * $_GET["nb_personnes"]) . "€";
                                    break;
                                case 3:
                                    echo "voiture ";
                                    echo -$price + ($price += 60 * $_GET["nb_personnes"]) . "€";
                                    break;
                                case 4:
                                    echo "bateau ";
                                    echo -$price + ($price += 80 * $_GET["nb_personnes"]) . "€";
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

    echo "<form action='../payement.php' method='post'>
    <input type='hidden' value='".$price."' name='price'>
    <input type='hidden' value='".$_SESSION["id"] . $voyage["id"] . implode('', array_map(function () {
            return dechex(rand(0, 15));
        }, range(1, 10)))."' name='id'>
    <button type='submit'>Payement</button>
</form>
";
}


?>


