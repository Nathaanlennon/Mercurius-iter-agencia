<?php

include "getapikey/getapikey.php";

$queue_dir = "php/queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($_GET["status"] === "accepted") {
        $voyage["id"] = substr($_GET["transaction"], 1, 1);
        $file = json_decode(file_get_contents("json/voyagetest.json"), true);
        foreach ($file as $trip) {
            if ($trip["id"] == $voyage["id"]) {
                $voyage = $trip;
                break;
            }
        }
        echo "<h1>Paiement effectué avec succès</h1>";
        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
        file_put_contents($queue_file, json_encode(["id" => (substr($_GET["transaction"], 0, 1)), "voyages" => [$voyage["name"] => ["payé" => true]]], JSON_PRETTY_PRINT));
        session_start();
        $_SESSION["voyages"][$voyage["name"]]["payé"] = true;
        echo "<a href='Php/index.php'>Retour à la page d'accueil</a>";
    } else if ($_GET["status"] === "denied") {
        echo "<h1>Erreur lors du paiement</h1>";
    }
} else if (($_SERVER["REQUEST_METHOD"] != "POST") || !isset($_POST["price"]) || !isset($_POST["id"])) {
    header("location:Php/index.php");
    exit();
} else {
    $transaction = $_POST["id"];
    $montant = $_POST["price"];
    $vendeur = 'MI-3_E';
    $retour = '
http://localhost/Mercurius-iter-agencia/payement.php
';
    $api_key = getAPIKey($vendeur);
    $control = md5(
        $api_key
        . "#" . $transaction
        . "#" . $montant
        . "#" . $vendeur
        . "#" . $retour . "#");

    echo "
<form action='https://www.plateforme-smc.fr/cybank/index.php'
      method='POST'>
    <input type='hidden' name='transaction'
           value='" . $transaction . "'>
    <input type='hidden' name='montant' value='" . $montant . "'>
    <input type='hidden' name='vendeur' value='" . $vendeur . "'>
    <input type='hidden' name='retour'
           value='" . $retour . "'>
    <input type='hidden' name='control'
           value='" . $control . "'>
    <input type='submit' value='Valider et payer'>
</form>";
}
?>