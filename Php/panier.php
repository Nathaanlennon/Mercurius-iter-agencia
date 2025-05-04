<?php
include "header.php";
include "../script/trip_calcul.php";


$queue_dir = "../queue"; // Dossier de la queue
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}
if (!isset($_SESSION['panier'])) {
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
    if ($details['payé']) {
        continue;
    }
    parse_str($details['config'], $config);
    echo "<h2>" . $voyage . "</h2>"
        . "prix : ";
    echo -$price + $price += (calculer_prix_total($details['duree'], $config));
}

echo "<h2>Prix total : " . $price . "€</h2>";
echo "<form action='payement.php' method='post'>
    <input type='hidden' value='" . $price . "' name='price'>
    <input type='hidden' value='" . $_SESSION["id"] . $config["id"] . (1). implode('', array_map(function () { // 1 veut sdire que c'est une transaction de panier
        return dechex(rand(1, 15));
    }, range(1, 9))) . "' name='id'>
    <button type='submit'>Payement</button>
</form>";
echo "</div>";
echo "</div>";
