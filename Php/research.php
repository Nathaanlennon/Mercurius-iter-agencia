<?php
include "header.php";

function has_city($trip, $city){
    if (isset($trip["stages"])){
        foreach ($trip["stages"] as $trip_city){
            if ($trip_city === $city){
                return true;
            }
        }
    }
    return false;
}

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

<div class="content">

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["action"])){
        echo "<div id=\"result\">";
        $file = file_exists("../voyagetest.json") ? json_decode(file_get_contents("../voyagetest.json"), true) : [];
        if ($file == null || $file == []) {
            echo "<p>Aucun voyage ne correspond à votre recherche.</p>";
        }
        else{
            foreach ($file as $voyage) {
                if (isset($_GET["price"])) {
                    if(!($voyage["price"] <= $_GET["price"])){
                        continue;
                    }
                }
                if(isset($_GET["cities"])){
                    foreach ($_GET["cities"] as $city){
                        if (!(has_city($voyage, $city))){
                            continue 2;
                        }
                    }
                }
                echo "<p>".$voyage["name"]."</p>";
            }
        }
        echo "</div>";
    }
    ?>
    <form method="get">
        <h6><label>Date de départ : <input type="date" name="depart" min=Date()></label>
        </h6>
        <h6><label>Date de retour : <input type="date" name="retour" min=Date()></label>
        </h6>

        <h6 class="titres">Villes souhaitées</h6>
        <div id="cities">
            <label>
                <input type="checkbox" name="cities[]" value="alexandrie">
                Alexandrie (Égypte)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="antioche">
                Antioche (Turquie)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="athenes">
                Athènes (Grèce)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="carthage">
                Carthage (Tunisie)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="constantinople">
                Constantinople (Istanbul, Turquie)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="ephese">
                Ephèse (Turquie)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="jerusalem">
                Jérusalem (Israël)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="leptis">
                Leptis Magna (Libye)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="rome">
                Rome (Italie)
            </label>
            <label>
                <input type="checkbox" name="cities[]" value="syracuse">
                Syracuse (Italie)
            </label>
        </div>
        <h6 class="titres">Niveau d'hotel :</h6>
        <div class="hotels">
            <label>Une étoile<input type="checkbox" value="un">
            </label>
            <label>Deux étoiles<input type="checkbox" value="deux">
            </label>
            <label>Trois étoiles<input type="checkbox" value="trois">
            </label>
            <label>Quatre étoiles<input type="checkbox" value="quatre">
            </label>
            <label>Cinq étoiles<input type="checkbox" value="cinq">
            </label>

        </div>
        <h6><label>budget :
                <input name="price" type="range" min="0" step="10" value="<?php
                echo isset($_GET["price"]) ? $_GET["price"] : 5000;
                ?>" max="10000"
                       oninput="this.nextElementSibling.value = this.value">
                <output>
                    <?php
                    echo isset($_GET["price"]) ? $_GET["price"] : 5000;
                    ?></output>
                €
            </label></h6>
        <button type="submit" name="action">Recherche</button>
    </form>
</div>

</body>
</html>