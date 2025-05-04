<?php
include "header.php";
$file = file_exists("../json/voyagetest.json") ? json_decode(file_get_contents("../json/voyagetest.json"), true) : [];

$fichier = "../json/voyagetest.json";

$voyages = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

$voyage_par_page = 5;
$total_voyages = count($voyages);

$total_voyages = count($voyages);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$debut = ($page - 1) * $voyage_par_page;
$voyages_page = array_slice($voyages, $debut, $voyage_par_page);

$total_pages = ceil($total_voyages / $voyage_par_page);


foreach ($voyages_page as $voyage) {
    echo "<div class='result' style=';' onclick='window.location=\"voyage_sheet.php?id=" . $voyage["id"] . "\"'>"
        . $voyage["name"] . "<br> Prix minimum (1 personnes) : " . $voyage["price"] . "€";
    echo "<br>".file_get_contents("../descript_voyage/".$voyage["id"] . "resume.txt");
    echo "</div>";
}
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de choix</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/choix.css">
</head>
<body>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">Précédent</a>
    <?php endif; ?>

    <span>Page <?= $page ?> sur <?= $total_pages ?></span>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>">Suivant</a>
    <?php endif; ?>
</div>

</body>
</html>