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
//        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
//        if (isset($_GET["nb_personnes"])) {
//            file_put_contents($queue_file, json_encode(["id" => $_SESSION["id"], "voyages" => [$voyage["name"] => ["payé" => false, "config" => $_SERVER['QUERY_STRING']]]], JSON_PRETTY_PRINT));
//            $_SESSION["voyages"][$voyage["name"]] = ["payé" => false, "config" => $_SERVER['QUERY_STRING']];
//        }

    }

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
            header("Location: choice.php");
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
    }
    header("Location: profile.php");
}

else {
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
<script>
    let price = Array.from({length: <?php echo count($voyage["stages"]) ?> }, () => Array(3).fill(0));
    let activities_price = [10, 20, 30, 0];
    let transports_price = [100, 60, 80, 50];
    let nb_personnes = 1;

    // Calcule le prix total d'un stage donné
    function calcul_stage(stage, nb_personnes) {
        // stage est un tableau : [niveauHotel, activités[], transport]
        let total = 0;

        // [0] : niveau hôtel
        const niveauHotel = stage[0] ?? 1;
        total += (2 ** (niveauHotel - 1)) * 25 * 2;

        // [1] : activités
        const activites = stage[1] ?? 0;
        for (let i = 0; i < 4; i++) {
            total += (activities_price[i] * ((activites & (1 << i)) !== 0));
        }

        // [2] : transport
        const transport = stage[2] ?? 1;
        total += transports_price[transport - 1]

        stage.forEach(s=>{
            console.log(typeof s);
        })

        return total*nb_personnes;
    }

    // Calcule le prix total pour tous les stages
    function calcul_price(data, nb_personnes = 1) {
        let total = 100*nb_personnes;

        data.forEach(stage => {
            total += calcul_stage(stage, nb_personnes);
        });

        return total;
    }


    window.addEventListener("load", function () {


        //init
        <?php

        for ($i = 0; $i < count($voyage["stages"]); $i++) {
            echo "price[" . $i . "][0] = " .(${$i . "1"} ?? 1). ";\n";

            if (isset(${$i . "2"})) {
                foreach (${$i . "2"} as $activity) {
                    switch ($activity) {
                        case 1:
                            echo "price[" . $i . "][1] += ".(1).";\n";
                            break;
                        case 2:
                            echo "price[" . $i . "][1] += ".(2).";\n";
                            break;
                        case 3:
                            echo "price[" . $i . "][1] += ".(4).";\n";
                            break;
                        case 4:
                            echo "price[" . $i . "][1] += ".(8).";\n";
                    }
                }
            }

            echo "price[" . $i . "][2] =". (${$i . "3"}??1) ."\n";

        }

        ?>
        nb_personnes = <?php echo($nb_personnes ?? 1) ?>;
        document.getElementById("avion").textContent = (nb_personnes*100).toString();

        //fin init

        console.log(price);

        /** @type {HTMLInputElement} */
        const number_input = document.getElementById("nb_personnes");
        number_input.addEventListener("change", ()=>{
            nb_personnes = number_input.value;
            console.log(nb_personnes)
            document.getElementById("avion").textContent = (nb_personnes*100).toString();
            document.getElementById("price").textContent = calcul_price(price, nb_personnes).toString();

        });
        document.getElementById("price").textContent = calcul_price(price, nb_personnes).toString();


        const trips = document.getElementById("form").querySelectorAll('div.trip');
        trips.forEach((trip, index_trip) => {
            const champs = trip.querySelectorAll('select, input, button');
            console.log(champs);


            champs.forEach(champ => {
                champ.addEventListener('input', () => {
                    switch (champ.nodeName) {
                        case 'SELECT':
                            // console.log("select"+ index_trip);
                            price[index_trip][0] = champ.value;
                            break;
                        case 'INPUT':
                            // console.log("input"+ index_trip);
                            switch (champ.type) {
                                case 'checkbox':
                                    console.log("checkbox" + index_trip);
                                    price[index_trip][1] ^= (1<<champ.value-1);
                                    break;
                                case 'radio':
                                    // console.log("radio" + index_trip);
                                    price[index_trip][2] = champ.value;
                                    break;
                            }
                            break;
                    }
                    console.log(price);
                    document.getElementById("price").textContent = calcul_price(price, nb_personnes).toString();
                })

            });

        });

        //ici on met à jour l'url
        const form = document.getElementById("form");

        form.addEventListener("input", (event) => {
            const input = event.target;
            const name = input.name;
            const currentParams = new URLSearchParams(window.location.search);

            if (input.type === "checkbox") {
                const checkboxes = form.querySelectorAll(`input[name="${name}"]`);
                const selected = [];

                checkboxes.forEach(cb => {
                    if (cb.checked) selected.push(cb.value);
                });

                currentParams.delete(name);
                selected.forEach(val => currentParams.append(name, val));

            } else if (input.type === "radio") {
                currentParams.set(name, input.value);

            } else {
                if (input.value) {
                    currentParams.set(name, input.value);
                } else {
                    currentParams.delete(name);
                }
            }

            const newUrl = window.location.pathname + "?" + currentParams.toString();
            history.replaceState({}, "", newUrl);
        });

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
            <label for="nb_personnes">Nombre de personnes : <input type="number" name="nb_personnes" id="nb_personnes" min="1" max="10"
                                                                   value="<?php echo($nb_personnes ?? 1) ?>"
                                                                   required></label>
            <br>
            <br>
            <b>Avion de départ : <span id="avion"></span>€</b>
            <?php

            for ($i = 0; $i < count($voyage["stages"]); $i++) {
                echo "<div class='trip' id='" . $i . "'>";
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
                echo "</div>"; //le div démoniaque
            }
            echo "<b>Prix total : <span id=\"price\">0</span>€</b>";
            echo "<br><button type=\"submit\" onClick='window.location.href=\"profile.php\")'>Valider</button>";
            ?>

    </div>

</div>
</body>
</html>