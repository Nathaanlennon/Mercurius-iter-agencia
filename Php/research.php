<?php


function has_city($trip, $city)
{
    if (isset($trip["stages"])) {
        foreach ($trip["stages"] as $trip_city) {
            if ($trip_city === $city) {
                return true;
            }
        }
    }
    return false;
}

function word_in_key_word($word, $key_word)
{
    $word = strtolower($word);
    foreach ($key_word as $key) {
        if ($word == strtolower($key)) {
            return true;
        }
    }
    return false;
}


$file = file_exists("../json/voyagetest.json") ? json_decode(file_get_contents("../json/voyagetest.json"), true) : [];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/research.css">
</head>
<body>
<?php
include "header.php";
?>
<script src="../js/research.js"></script>
<script>


    let nb_personnes = <?= $_GET["nb_utilisateurs"] ?? 1 ?>;




</script>

<div class="content">

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["action"])) {
        echo "<div id=\"result\">";
        if ($file == null || $file == []) {
            echo "<p>Aucun voyage ne correspond à votre recherche.</p>";
        } else {
            echo "<form id='tri'>"
                . "<label for='tri'>Trier par : </label>"
                . "<select name='type' id='type'>"
                . "<option value='prix'>Prix</option>"
                . "<option value='duree'>Durée</option>"
                . "<option value='nom'>Nom</option>"
                . "</select>"

                . "<label for='ordre'>Ordre : </label>"
                . "<select name='ordre' id='ordre'>"
                . "<option value='croissant'>Croissant</option>"
                . "<option value='decroissant'>Décroissant</option>"
                . "</select>"

                . "</form>"
                . "<div id='trips'>";


            foreach ($file as $voyage) {
                if (isset($_GET["price"])) {
                    if (!($voyage["price"] * ($_GET["nb_utilisateurs"] ?? 1) <= $_GET["price"])) {
                        continue;
                    }
                }
                if (isset($_GET["stages"])) {
                    foreach ($_GET["stages"] as $city) {
                        if (!(has_city($voyage, $city))) {
                            continue 2;
                        }
                    }
                }
                if (isset($_GET["depart"]) && $_GET["depart"] != '' && isset($_GET["retour"]) && $_GET["retour"] != '') {

                    if ((strtotime($_GET["retour"]) - strtotime($_GET["depart"])) / (60 * 60 * 24) < $voyage["duration"]) {
                        continue;
                    }
                }
                if (isset($_GET["key-word"]) && $_GET["key-word"] != '') {
                    $key_word = explode(" ", $_GET["key-word"]);
                    foreach ($key_word as $word) {
                        if (!(word_in_key_word($word, $voyage["key-word"])) && !(strtolower($word) == strtolower($voyage["name"]))) {
                            continue 2;
                        }
                    }
                }
                echo "<script>trips.push({name: \"" . addslashes($voyage['name']) . "\", id: {$voyage['id']}, price: {$voyage['price']}, duration: {$voyage['duration']}});</script>";
                echo "<div class='result' style=';' onclick='window.location=\"voyage_sheet.php?id=" . $voyage["id"] . "\"'>"
                    . $voyage["name"] . "<br> Prix minimum (" . ($_GET["nb_utilisateurs"] ?? 1) . ") : " . $voyage["price"] * ($_GET["nb_utilisateurs"] ?? 1) . "€";
                echo "<br>" . file_get_contents("../descript_voyage/" . $voyage["id"] . "resume.txt");
                echo "</div>";
            }
            echo "</div>";
            echo "<script>console.log(trips);\nprint_trips(trips)</script>";
        }
        echo "</div>";
    }
    ?>
    <form method="get" class="research">
        <h6><label for="key-word">Mots clés : <input type="text" name="key-word"
                                                     value="<?php echo($_GET["key-word"] ?? '') ?>"></label>
        </h6>
        <h6><label>Date de départ : <input type="date" name="depart"
                                           min=Date() <?php echo(isset($_GET["depart"]) ? "value='" . $_GET["depart"] . "'" : '') ?>
            </label>
        </h6>
        <h6><label>Date de retour : <input type="date" name="retour"
                                           min=Date() <?php echo(isset($_GET["retour"]) ? "value='" . $_GET["retour"] . "'" : '') ?>
            </label>
        </h6>
        <h6><label for="nb_personnes">Nombre de personnes : <input type="number" name="nb_utilisateurs" min="1" max="10"
                                                                   value="<?= $_GET["nb_utilisateurs"] ?? 2 ?>"
                                                                   required></label>
        </h6>
        <h6 class="titres">Villes souhaitées</h6>
        <div id="stages">

            <?php
            for ($i = 0; $i < count($file[0]["stages"]); $i++) {
                echo "<label><input type=\"checkbox\" name=\"stages[]\" value=\"" . $file[0]["stages"][$i] . "\"" . (isset($_GET["stages"]) && has_city($_GET, $file[0]["stages"][$i]) ? "checked" : "") . ">" . $file[0]["stages"][$i] . "</label>";

            }
            ?>

        </div>


        <h6><label>budget :
                <input name="price" type="range" min="0" step="10" value="<?php
                echo $_GET["price"] ?? 5000;
                ?>" max="10000"
                       oninput="this.nextElementSibling.value = this.value">
                <output>
                    <?php
                    echo $_GET["price"] ?? 5000;
                    ?></output>
                €
            </label></h6>
        <button type="submit" name="action">Recherche</button>
    </form>
</div>

</body>
</html>