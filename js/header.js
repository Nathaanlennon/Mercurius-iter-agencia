// Fonctions utilitaires pour les cookies

// Définit un cookie avec un nom, une valeur et une durée en jours
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        // Calcule la date d'expiration en millisecondes
        date.setTime(date.getTime() + (days * 86400000)); // 24*60*60*1000
        expires = "; expires=" + date.toUTCString();
    }
    // Crée le cookie avec le chemin défini pour tout le site
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

// Récupère la valeur d’un cookie en fonction de son nom
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';'); // Sépare les différents cookies
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim(); // Supprime les espaces
        // Vérifie si le cookie correspond au nom recherché
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
    }
    return null; // Retourne null si le cookie n’existe pas
}

// Logique pour activer/désactiver le thème (sombre ou clair)
window.addEventListener("DOMContentLoaded", () => {
    // Récupère le thème actuel depuis les cookies
    const currentTheme = getCookie("theme");
    if (currentTheme === "dark") {
        // Applique le thème sombre si trouvé dans les cookies
        document.documentElement.setAttribute("data-theme", "dark");
    }

    // Écoute le clic sur le bouton de changement de thème
    document.getElementById("theme-toggle").addEventListener("click", () => {
        // Vérifie si le thème actuel est sombre
        const isDark = document.documentElement.getAttribute("data-theme") === "dark";
        // Bascule entre clair et sombre
        document.documentElement.setAttribute("data-theme", isDark ? "light" : "dark");
        // Sauvegarde le choix dans les cookies pour 30 jours
        setCookie("theme", isDark ? "light" : "dark", 30);
    });
});
