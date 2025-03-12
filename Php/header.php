<?php
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
            <a href="presentation.php" class="purple">À propos</a>
            <a href="research.php" class="purple">Recherche</a>
            <a href="choice.php" class="red">Choix</a>
        </nav>
    </header>

</div>

