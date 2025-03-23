<?php
session_start();


$info_util = $_SESSION;

$fichier = "../json/utilisateurs.json";

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

if (isset($_COOKIE["sans-gluten"])&&(!isset($_SESSION['id']))) {
    foreach ($utilisateurs as $utilisateur) {
        if ($_COOKIE["sans-gluten"] == $utilisateur["id"]) {
            if ($utilisateur["role"] == "Banni") {
                echo "Vous avez été banni";
                exit;
            }

            $_SESSION["id"] =  (isset($utilisateur["id"]) ? $utilisateur["id"]:[]);
            $_SESSION["email"] =(isset($utilisateur["email"]) ? $utilisateur["email"]:[]);
            $_SESSION["nom"] = (isset($utilisateur["nom"]) ? $utilisateur["nom"]:[]);
            $_SESSION["role"] = (isset($utilisateur["role"]) ? $utilisateur["role"]:[]);
            $_SESSION["voyages"] = (isset($utilisateur["voyages"]) ? $utilisateur["voyages"]:[]);
        }
    }
}
?>

<link rel="stylesheet" href="../Css/style.css">

<div>
    <header>
        <div class="header">
            <div id="logo"><a href="index.php"><img src="../assets/logo.png" alt="logo"></a></div>

            <h1>Mercurius Iter Agencia</h1>

            <div id="temp">
                <div>

                </div>
                <div class="auth">
                    <?php
                    if (isset($_SESSION['id'])) {
                        echo '<a href="profile.php" class="red">Profil</a>';
                        echo '<a href="deconnexion.php" class="red">Déconnexion</a>';
                    } else {
                        echo '<a href="inscription.php" class="red">Inscription</a>';
                        echo '<a href="connexion.php" class="red">Connexion</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <nav>
            <?php
            if (isset($info_util['role']) && $info_util['role'] === "admin") {
                echo '<a href="admin.php">Admin</a>';
            }
            ?>
            <a href="presentation.php" class="purple">À propos</a>
            <a href="research.php" class="purple">Recherche</a>
            <a href="choice.php" class="red">Choix</a>
        </nav>
    </header>

</div>

