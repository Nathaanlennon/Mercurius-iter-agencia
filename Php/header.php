<?php
session_start();

$info_util = $_SESSION;

$fichier = "../json/utilisateurs.json";

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

if (isset($_COOKIE["sans-gluten"]) && !isset($_SESSION['id'])) {
    foreach ($utilisateurs as $utilisateur) {
        if ($_COOKIE["sans-gluten"] == $utilisateur["id"]) {
            if ($utilisateur["role"] == "Banni") {
                echo "Vous avez été banni";
                exit;
            }

            $_SESSION["id"] = $utilisateur["id"] ?? [];
            $_SESSION["email"] = $utilisateur["email"] ?? [];
            $_SESSION["nom"] = $utilisateur["nom"] ?? [];
            $_SESSION["role"] = $utilisateur["role"] ?? [];
            $_SESSION["voyages"] = $utilisateur["voyages"] ?? [];
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
                <div></div>
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
            <button id="theme-toggle">Mode sombre</button>
        </nav>
    </header>
</div>

<script>
    // Cookie helpers
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 86400000)); // 24*60*60*1000
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
        }
        return null;
    }

    // Theme toggle logic
    window.addEventListener("DOMContentLoaded", () => {
        const currentTheme = getCookie("theme");
        if (currentTheme === "dark") {
            document.documentElement.setAttribute("data-theme", "dark");
        }

        document.getElementById("theme-toggle").addEventListener("click", () => {
            const isDark = document.documentElement.getAttribute("data-theme") === "dark";
            document.documentElement.setAttribute("data-theme", isDark ? "light" : "dark");
            setCookie("theme", isDark ? "light" : "dark", 30);
        });
    });
</script>
