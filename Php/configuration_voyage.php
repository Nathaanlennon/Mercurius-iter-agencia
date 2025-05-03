<?php
include "header.php";


$queue_dir = "../queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {


    $voyage["id"] = $_GET["id"];
    if (file_exists("../json/voyagetest.json")) {
        $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
        foreach ($file as $trip) {
            if ($trip["id"] == $voyage["id"]) {
                $voyage = $trip;
                break;
            }
        }
        if (!isset($_SESSION["voyages"]) || ((isset($_SESSION["voyages"][$voyage["name"]]) && $_SESSION["voyages"][$voyage["name"]]["payé"]))) {
            header("Location: choice.php");
            exit();
        }
        if (isset($_GET["depart"])) {
            $depart = $_GET["depart"];
        }

        if (isset($_GET["nb_personnes"])) {
            $nb_personnes = $_GET["nb_personnes"];
        }
        for ($i = 0; $i < count($voyage["stages"]); $i++) {
            for ($j = 1; $j < 4; $j++) {
                if (isset($_GET[$i . $j])) {
                    ${$i . $j} = $_GET[$i . $j];

                }
            }
        }
        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
        if(isset($_GET["nb_personnes"])){
            file_put_contents($queue_file, json_encode(["id" => $_SESSION["id"], "voyages" => [$voyage["name"] => ["payé" => false, "config" => $_SERVER['QUERY_STRING']]]], JSON_PRETTY_PRINT));
            $_SESSION["voyages"][$voyage["name"]] = ["payé" => false, "config" => $_SERVER['QUERY_STRING']];
        }

    }

} else {
    header("Location: choice.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/trip_config.css">
</head>
<body>
<div class="content">
    <div class="configuration">
        <h1><?php echo $voyage["name"] ?></h1>
        <form action="configuration_voyage.php" method="get">
            <input type="hidden" name="id" value="<?php echo $voyage["id"] ?>">
            <label for="depart">Date de départ : <input type="date" name="depart" min="<?php echo date('Y-m-d') . "\"
                                                value=\"" . (isset($depart) ? $depart : date('Y-m-d')) ?>"
                                                        required></label>
            <label for="nb_personnes">Nombre de personnes : <input type="number" name="nb_personnes" min="1" max="10"
                                                                   value="<?php echo(isset($nb_personnes) ? $nb_personnes : 1) ?>"
                                                                   required></label>
            <br>
            <br>
            <?php

            for ($i = 0; $i < count($voyage["stages"]); $i++) {
                echo "<div class='trip'>";
                $stage = $voyage["stages"][$i];
                echo "<span class='name'>" . $stage . " :</span><br>";
                echo "niveau hotel : <select name=\"" . $i . "1\">
    <option value=\"1\"" . ((isset(${$i . "1"}) && (${$i . "1"} != "1")) ? '' : 'selected') . ">1</option>
    <option value=\"2\"" . ((isset(${$i . "1"}) && (${$i . "1"} == "2")) ? 'selected' : '') . ">2</option>
    <option value=\"3\"" . ((isset(${$i . "1"}) && (${$i . "1"} == "3")) ? 'selected' : '') . ">3</option>
    <option value=\"4\"" . ((isset(${$i . "1"}) && (${$i . "1"} == "4")) ? 'selected' : '') . ">4</option>
    <option value=\"5\"" . ((isset(${$i . "1"}) && (${$i . "1"} == "5")) ? 'selected' : '') . ">5</option>
</select><br>";

                echo "activités : 
    <label><input type='checkbox' name=\"" . $i . "2[]\" value=\"1\"" . ((isset(${$i . "2"}) && in_array("1", ${$i . "2"})) ? ' checked' : '') . "> musée</label>
    <label><input type='checkbox' name=\"" . $i . "2[]\" value=\"2\"" . ((isset(${$i . "2"}) && in_array("2", ${$i . "2"})) ? ' checked' : '') . "> visite de ruines</label>
    <label><input type='checkbox' name=\"" . $i . "2[]\" value=\"3\"" . ((isset(${$i . "2"}) && in_array("3", ${$i . "2"})) ? ' checked' : '') . "> spectacle</label>
    <label><input type='checkbox' name=\"" . $i . "2[]\" value=\"4\"" . ((isset(${$i . "2"}) && in_array("4", ${$i . "2"})) ? ' checked' : '') . "> plage</label>";

                echo "<br> transport : 
    <label><input type='radio' name=\"" . $i . "3\" value=\"1\"" . ((isset(${$i . "3"}) && (${$i . "3"} != "1")) ? '' : 'checked') . "> avion</label>
    <label><input type='radio' name=\"" . $i . "3\" value=\"2\"" . ((isset(${$i . "3"}) && (${$i . "3"} == "2")) ? 'checked' : '') . "> voiture</label>
    <label><input type='radio' name=\"" . $i . "3\" value=\"3\"" . ((isset(${$i . "3"}) && (${$i . "3"} == "3")) ? 'checked' : '') . "> bateau</label>
    <label><input type='radio' name=\"" . $i . "3\" value=\"4\"" . ((isset(${$i . "3"}) && (${$i . "3"} == "4")) ? 'checked' : '') . "> train</label><br>";
                echo "</span>";
            }

            echo "<br><button type=\"submit\">Valider</button> </button>";
            ?>
        </form>
    </div>

</div>
</body>
</html>