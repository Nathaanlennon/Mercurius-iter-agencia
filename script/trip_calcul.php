<?php
function calculer_prix_total($duree, array $params): int
{
    $price = 0;

    // Prix de base avion aller
    $price += 100 * $params["nb_personnes"];

    // Tableau des prix en cohérence avec le JS
    $activities_price = [10, 20, 30, 0];        // index 0 = musée, 1 = ruines, 2 = spectacle, 3 = plage
    $transports_price = [100, 60, 80, 50];      // index 0 = avion, 1 = voiture, 2 = bateau, 3 = train
    if (file_exists("../json/voyagetest.json") && file_exists("../json/villes_activites.json")) {
        $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
        $file2 = json_decode(file_get_contents("../json/villes_activites.json"), true);
        if (isset($file) && isset($file2)) {
            $voyage = [];

            foreach ($file as $trip) {
                if ($trip["id"] == (int)$params["id"]) {
                    $voyage = $trip;
                    break;
                }
            }

            // Pour chaque étape
            for ($i = 0; $i < $duree; $i++) {
                foreach ($file2 as $stage) {
                    if ($stage["name"] == $voyage["stages"][$i]) {
//                            print_r($stage);
                        $ville = [$stage["activities"], $stage["price"]];
                        break;
                    }
                }
                for ($j = 1; $j < 4; $j++) {
                    if (isset($params[$i . $j])) {
                        switch ($j) {
                            case 1: // Hôtel
                                $stars = $params[$i . $j];
                                $price += (2 ** ($stars - 1)) * 25 * $params["nb_personnes"] * 2;
                                break;

                            case 2: // Activités
                                foreach ($params[$i . $j] as $activity) {
                                    $index = (int)$activity - 1;
                                    if (isset($ville[0][$index])) {
                                        $price += $ville[1][$index] * $params["nb_personnes"];
                                    }
                                }
                                break;

                            case 3: // Transport
                                $index = (int)$params[$i . $j] - 1;
                                if (isset($transports_price[$index])) {
                                    $price += $transports_price[$index] * $params["nb_personnes"];
                                }
                                break;
                        }
                    }
                }
            }

        }
    }


    return $price;
}
