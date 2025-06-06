<?php
include "header.php";
include "../getapikey/getapikey.php";


$info_util = $_SESSION;
if (!isset($info_util['id'])) {
    header("Location: connexion.php");
    exit;
}

$queue_dir = "../queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}
?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Réservation</title>
        <link rel="stylesheet" href="../Css/style.css">
        <link rel="stylesheet" href="../Css/trip_sheet.css">
    </head>
<?php
//la méthode get correspond au retour du payement après passage à cybank
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($_GET["status"] === "accepted") {
        echo "<div class='content'>";
        echo "<div id='recap'>";
        switch (substr($_GET['transaction'], 0, 1)){
            //cas 0 pour un seul voyage en normal
            case 0:
                //mise à jour de la base de donnée
                $voyage["id"] = substr($_GET["transaction"], 4, substr($_GET["transaction"], 3, 1));
                //on suit la logique de l'id transaction
                $file = json_decode(file_get_contents("../json/voyagetest.json"), true);
                foreach ($file as $trip) {
                    if ($trip["id"] == $voyage["id"]) {
                        $voyage = $trip;
                        break;
                    }
                }
                echo "<h1>Paiement effectué avec succès</h1>";
                $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
                file_put_contents($queue_file, json_encode(["id" => (substr($_GET["transaction"], 2, substr($_GET["transaction"], 1, 1))), "voyages" => [$voyage["name"] => ["payé" => true]]], JSON_PRETTY_PRINT));

                $_SESSION["voyages"][$voyage["name"]]["payé"] = true;

                if(isset($_SESSION['panier'][$voyage["name"]])) {
                    unset($_SESSION['panier'][$voyage["name"]]);
                }
                break;
                //cas 1 pour utilisation du panier
            case 1:
                echo "<h1>Paiement effectué avec succès</h1>";
                //mise à jour de la base de donnée
                foreach ($_SESSION['panier'] as $key => $tab) {
                    $_SESSION["voyages"][$key]["payé"] = true;
                    $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
                    file_put_contents($queue_file, json_encode(["id" => (substr($_GET["transaction"], 2, substr($_GET["transaction"], 1, 1))), "voyages" => [$key => ["payé" => true]]], JSON_PRETTY_PRINT));
                    unset($_SESSION['panier'][$key]);
                }
                break;
        }




        echo "<a href='index.php'>Retour à la page d'accueil</a>";
        echo "</div>";
        echo "</div>";
    } else if ($_GET["status"] === "denied") {
        echo "<h1>Erreur lors du paiement</h1>";
    }

    //méthode post pour envoyer la requête de paiement
} else if (($_SERVER["REQUEST_METHOD"] != "POST") || !isset($_POST["price"]) || !isset($_POST["id"])) {
    header("location:index.php");
    exit();
} else {
    $transaction = $_POST["id"];
    $montant = $_POST["price"];
    $vendeur = 'MI-3_E';
    $retour = '
http://localhost/Mercurius-iter-agencia/Php/payement.php
';
    $api_key = getAPIKey($vendeur);
    $control = md5(
        $api_key
        . "#" . $transaction
        . "#" . $montant
        . "#" . $vendeur
        . "#" . $retour . "#");

    echo "<p>transaction : ". $transaction . "<br> Montant : ".$montant."€<br>vendeur : " . $vendeur."</p>

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