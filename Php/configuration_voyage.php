<?php
include "header.php";

$queue_dir = "../queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {
    $voyage["id"] = $_GET["id"];
    if (file_exists("../json/voyagetest.json")) {
        $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
        if(!$file || $voyage["id"]>= count($file)){
            header("Location: index.php");
            exit();
        }
        foreach ($file as $trip) {
            if ($trip["id"] == $voyage["id"]) {
                $voyage = $trip;
                break;
            }
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

//
    }
//pour enregistrer les données, on récupère les valeurs est on met à jour la base de donnée
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["id"])) {

    $voyage["id"] = $_POST["id"];
    if (file_exists("../json/voyagetest.json")) {
        $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
        foreach ($file as $trip) {
            if ($trip["id"] == $voyage["id"]) {
                $voyage = $trip;
                break;
            }
        }
        if (!isset($_SESSION["voyages"]) || (isset($_SESSION["voyages"][$voyage["name"]]) && $_SESSION["voyages"][$voyage["name"]]["payé"])) {
            header("Location: connexion.php?");
            exit();
        }
        if (isset($_POST["depart"])) {
            $depart = $_POST["depart"];
        }

        if (isset($_POST["nb_personnes"])) {
            $nb_personnes = $_POST["nb_personnes"];
        }

        for ($i = 0; $i < count($voyage["stages"]); $i++) {
            for ($j = 1; $j < 4; $j++) {
                if (isset($_POST[$i . $j])) {
                    ${$i . $j} = $_POST[$i . $j];
                }
            }
        }

        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
        if (isset($_POST["nb_personnes"])) {
            file_put_contents($queue_file, json_encode([
                "id" => $_SESSION["id"],
                "voyages" => [
                    $voyage["name"] => [
                        "payé" => false,
                        "config" => http_build_query($_POST)
                    ]
                ]
            ], JSON_PRETTY_PRINT));
            $_SESSION["voyages"][$voyage["name"]] = [
                "payé" => false,
                "config" => http_build_query($_POST)
            ];
        }
        if (isset($_SESSION['panier'])) {
            $trip_panier = -1;
            foreach ($_SESSION['panier'] as $key=> $stage) {
                if($key == $voyage["name"]) {
                    $_SESSION['panier'][$key]=["payé" => false,
                        "config" => http_build_query($_POST)];
                    $_SESSION['panier'][$key]['duree'] = (count($voyage["stages"]));
                    $_SESSION['panier'][$key]['id'] = $voyage["id"];
                    $trip_panier = 1;
                    break;
                }
            }
            if($trip_panier == -1){
                $_SESSION['panier'][$voyage['name']] = ["payé" => false,
                    "config" => http_build_query($_POST), "duree" => (count($voyage["stages"])), "id" => $voyage["id"]];
            }
        }
        else{
            $_SESSION['panier'][$voyage['name']] = ["payé" => false,
                "config" => http_build_query($_POST), "duree" => (count($voyage["stages"])), "id" => $voyage["id"]];
        }
    }
    header("Location: profile.php");
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

<script>
    let price = Array.from({length: <?php echo count($voyage["stages"]) ?> }, () => Array(3).fill(0));

    let stages = <?= json_encode($voyage["stages"]) ?>;




    window.addEventListener("load", function () {


        //init
        <?php

        for ($i = 0; $i < count($voyage["stages"]); $i++) {
            echo "price[" . $i . "][0] = " . (${$i . "1"} ?? 1) . ";\n";

            if (isset(${$i . "2"})) {
                foreach (${$i . "2"} as $activity) {
                    // du binaire bitflag
                    switch ($activity) {
                        case 1:
                            echo "price[" . $i . "][1] += " . (1) . ";\n";
                            break;
                        case 2:
                            echo "price[" . $i . "][1] += " . (2) . ";\n";
                            break;
                        case 3:
                            echo "price[" . $i . "][1] += " . (4) . ";\n";
                            break;
                        case 4:
                            echo "price[" . $i . "][1] += " . (8) . ";\n";
                    }
                }
            }

            echo "price[" . $i . "][2] =" . (${$i . "3"} ?? 1) . "\n";

        }

        ?>
        nb_personnes = <?php echo($nb_personnes ?? 1) ?>;


        //fin init
    });


</script>

<div class="content">
    <div class="configuration">
        <h1><?php echo $voyage["name"] ?></h1>
        <form action="configuration_voyage.php" method="post" id="form">
            <input type="hidden" name="id" value="<?php echo $voyage["id"] ?>">
            <label for="depart">Date de départ : <input type="date" name="depart" min="<?php echo date('Y-m-d') . "\"
                                                value=\"" . ($depart ?? date('Y-m-d')) ?>"
                                                        required></label>
            <label for="nb_personnes">Nombre de personnes : <input type="number" name="nb_personnes" id="nb_personnes"
                                                                   min="1" max="10"
                                                                   value="<?php echo($nb_personnes ?? 1) ?>"
                                                                   required></label>
            <br>
            <br>
            <b>Avion de départ : <span id="avion"></span>€</b>



            <?php

            for ($i = 0; $i < count($voyage["stages"]); $i++) {
                echo "<div class='trip hidden' id='" . $i . "'>";
                echo "</div>"; //le div démoniaque
            }
            echo "<b>Prix total : <span id=\"price\">0</span>€</b>";
            echo "<br><button type=\"submit\" onClick='window.location.href=\"profile.php\")'>Valider</button>";
            ?>

    </div>

</div>
<script src="../JavaScript/configuration_voyage.js"></script>
</body>
</html>