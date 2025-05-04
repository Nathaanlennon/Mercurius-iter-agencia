<?php
include "header.php"; // Inclusion du fichier d'en-tête

// Chargement des données du fichier JSON, si le fichier existe
$file = file_exists("../json/voyagetest.json") ? json_decode(file_get_contents("../json/voyagetest.json"), true) : [];

// Définition du chemin du fichier des voyages
$fichier = "../json/voyagetest.json";

// Chargement des voyages à partir du fichier JSON
$voyages = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

// Définition du nombre de voyages à afficher par page
$voyage_par_page = 5;
// Calcul du nombre total de voyages
$total_voyages = count($voyages);

// Vérification et récupération de la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// S'assurer que la page est valide (au moins 1)
$page = max(1, $page);

// Calcul du voyage à afficher en fonction de la page actuelle
$debut = ($page - 1) * $voyage_par_page;
// Découper le tableau des voyages pour la page actuelle
$voyages_page = array_slice($voyages, $debut, $voyage_par_page);

// Calcul du nombre total de pages nécessaires
$total_pages = ceil($total_voyages / $voyage_par_page);

// Affichage des voyages de la page actuelle
foreach ($voyages_page as $voyage) {
    // Générer le HTML pour chaque voyage
    echo "<div class='result' style=';' onclick='window.location=\"voyage_sheet.php?id=" . $voyage["id"] . "\"'>"
        . $voyage["name"] . "<br> Prix minimum (1 personnes) : " . $voyage["price"] . "€";

    // Lire et afficher le contenu du fichier de résumé du voyage
    echo "<br>" . file_get_contents("../descript_voyage/" . $voyage["id"] . "resume.txt");
    echo "</div>";
}
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de choix</title>
    <link rel="stylesheet" href="../Css/style.css"> <!-- Lien vers la feuille de style -->
    <link rel="stylesheet" href="../Css/choix.css"> <!-- Lien vers la feuille de style pour la page de choix -->
</head>
<body>
<div class="pagination">
    <!-- Affichage du lien "Précédent" si ce n'est pas la première page -->
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">Précédent</a>
    <?php endif; ?>

    <!-- Affichage de la page actuelle et du nombre total de pages -->
    <span>Page <?= $page ?> sur <?= $total_pages ?></span>

    <!-- Affichage du lien "Suivant" si ce n'est pas la dernière page -->
    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>">Suivant</a>
    <?php endif; ?>
</div>

</body>
</html>
