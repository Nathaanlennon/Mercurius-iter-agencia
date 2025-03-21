<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {
    $voyage["id"] = $_GET["id"];
    if (file_exists("../voyagetest.json")) {
        $file = json_decode(file_get_contents("../voyagetest.json"), true);
        foreach ($file as $trip) {
            if ($trip["id"] == $voyage["id"]) {
                $voyage = $trip;
                break;
            }
        }
        for ($i = 0; $i < count($voyage["stages"]); $i++) {
            for ($j = 1; $j < 4; $j++) {
                if (isset($_GET[$i . $j])) {
                    ${$i . $j} = $_GET[$i . $j];

                }
            }
        }
    }
} else {
    header("Location: choice.php");
    exit();
}

include "header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/research.css">
</head>
<body>
<h1><?php echo $voyage["name"] ?></h1>
<form action="configuration_voyage.php" method="get">
    <input type="hidden" name="id" value="<?php echo $voyage["id"] ?>">
    <label for="depart">Date de départ : <input type="date" name="depart" min="<?php echo date('Y-m-d'); ?>"></label>
    <label for="retour">Date de retour : <input type="date" name="retour"
                                                min="<?php echo date('Y-m-d', strtotime('+' . (int)$voyage['duration'] . ' days')); ?>"></label>
    <label for="nb_personnes">Nombre de personnes : <input type="number" name="nb_personnes" min="1" max="10"></label>
    <?php

    for ($i = 0; $i < count($voyage["stages"]); $i++) {
        $stage = $voyage["stages"][$i];
        echo $stage . " :<br>";
        echo "niveau hotel : <select name=\"" . $i . "1\">
 <option value=\"1\"". ((isset(${$i."1"}) && (${$i."1"}!="1")) ? '':'selected'). ">1</option>
<option value=\"2\"". ((isset(${$i."1"}) && (${$i."1"}=="2")) ? 'selected':''). ">2</option>
<option value=\"3\"". ((isset(${$i."1"}) && (${$i."1"}=="3")) ? 'selected':''). ">3</option>
<option value=\"4\"". ((isset(${$i."1"}) && (${$i."1"}=="4")) ? 'selected':''). ">4</option>
<option value=\"5\"". ((isset(${$i."1"}) && (${$i."1"}=="5")) ? 'selected':''). ">5</option>
</select><br>";
        echo "activitées : <input type='checkbox' name=\"" . $i . "2[]\" value=\"1\"" . ((isset(${$i . "2"}) && in_array("1", ${$i . "2"})) ? ' checked' : '') . ">musée
<input type='checkbox' name=\"" . $i . "2[]\" value=\"2\"" . ((isset(${$i . "2"}) && in_array("2", ${$i . "2"})) ? ' checked' : '') . ">visite de ruines
<input type='checkbox' name=\"" . $i . "2[]\" value=\"3\"" . ((isset(${$i . "2"}) && in_array("3", ${$i . "2"})) ? ' checked' : '') . ">spectacle
<input type='checkbox' name=\"" . $i . "2[]\" value=\"4\"" . ((isset(${$i . "2"}) && in_array("4", ${$i . "2"})) ? ' checked' : '') . ">plage";
        echo "transport : 
        <input type='radio' name=\"" . $i . "3\" value=\"1\"". ((isset(${$i."3"}) && (${$i."3"}!="1")) ? '':'checked') .">avion
        <input type='radio' name=\"" . $i . "3\" value=\"2\"". ((isset(${$i."3"}) &&  (${$i."3"}== "2"))? 'checked':'').">voiture
        <input type='radio' name=\"" . $i . "3\" value=\"3\"" . ((isset(${$i."3"}) && (${$i."3"} == "3")) ? 'checked' : '') . ">bateau
<input type='radio' name=\"" . $i . "3\" value=\"4\"" . ((isset(${$i."3"}) && (${$i."3"} == "4")) ? 'checked' : '') . ">train<br>";
        echo "<br>";
    }

    echo "<button type=\"submit\">Valider</button>";


    ?>
</form>
</body>
</html>