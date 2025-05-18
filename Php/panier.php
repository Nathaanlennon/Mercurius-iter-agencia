<?php
include "header.php";
include "../script/trip_calcul.php";
?>
<?php
$queue_dir = "../queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}

//vérifications que l'utilisateur est connecté et que le panier n'est pas vide
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header("Location: index.php");
    exit;
}
?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Panier</title>
        <link rel="stylesheet" href="../Css/style.css">
        <link rel="stylesheet" href="../Css/trip_sheet.css">
    </head>
    <body>


    <div class='content'>
        <div id='recap'>
            <h1>Mon Panier</h1>
<?php
$price = 0;
foreach ($_SESSION['panier'] as $voyage => $details) {
    if (!$details['payé']) {
        parse_str($details['config'], $config);
        echo "<h2>" . $voyage . "</h2>"
            . "Prix : ";
        //affiche le prix d'un voyage et le rajoute au total
        echo -$price + $price += (calculer_prix_total($details['duree'], $config));
    }
}

$nb_user = strlen((string)$_SESSION['id']);
$nb_trip = strlen((string)$config['id']);
echo "<h2>Prix total : " . $price . "€</h2>";
echo "<form action='payement.php' method='post'>
    <input type='hidden' value='" . $price . "' name='price'>";

//    id transaction fonctionne comme suivant : type (normal ou panier) puis nombre de chiffres pour l'id utilisateur ainsi que ce dernier puis nombre de chiffres pour l'id du voyage ainsi que ce dernier puis des nombres aléatoires pour completer
echo "<input type='hidden' value='" . (1) . $nb_user . $_SESSION["id"] . $nb_trip . $config["id"] . implode('', array_map(function () { // 1 veut sdire que c'est une transaction de panier
        return dechex(rand(1, 15));
    }, range(1, 7 - $nb_trip - $nb_user))) . "' name='id'>
    <button type='submit'>Payement</button>
</form>";
echo "</div>";
echo "</div>";
