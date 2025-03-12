<?php
include "header.php"
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

    <form action="">
        <h6><label>Date de départ : <input type="date" name="depart" min=Date()></label>
        </h6>
        <h6><label>Date de retour : <input type="date" name="retour" min=Date()></label>
        </h6>

        <h6 class="titres">Villes souhaitées</h6>
        <div id="cities">
            <label>
                <input type="checkbox" value="Alexandrie">
                Alexandrie (Égypte)
            </label>

            <label>
                <input type="checkbox" value="Antioche">
                Antioche (Turquie)
            </label>

            <label>
                <input type="checkbox" value="Athenes">
                Athènes (Grèce)
            </label>

            <label>
                <input type="checkbox" value="Carthage">
                Carthage (Tunisie)
            </label>

            <label>
                <input type="checkbox" value="Constantinople">
                Constantinople (Istanbul, Turquie)
            </label>

            <label>
                <input type="checkbox" value="Ephese">
                Ephèse (Turquie)
            </label>

            <label>
                <input type="checkbox" value="Jerusalem">
                Jérusalem (Israël)
            </label>

            <label>
                <input type="checkbox" value="LeptisMagna">
                Leptis Magna (Libye)
            </label>

            <label>
                <input type="checkbox" value="Rome">
                Rome (Italie)
            </label>

            <label>
                <input type="checkbox" value="Syracuse">
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
            <input type="range" min="0" step="10" value="5000" max="10000" oninput="this.nextElementSibling.value = this.value">
            <output>5000</output>€
        </label></h6>
        <button type="submit">Recherche</button>
    </form>
</div>
</body>
</html>