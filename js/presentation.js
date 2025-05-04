// Fonction qui met à jour les codes couleurs affichés selon le thème actuel
const updateColorCodes = () => {
    // Vérifie si le thème actuel est sombre
    const isDark = document.documentElement.getAttribute("data-theme") === "dark";

    // Définit les couleurs hexadécimales en fonction du thème
    const colors = {
        red: isDark ? "#660000" : "#9a0000",
        purple: isDark ? "#3d003d" : "#650065",
        yellow: isDark ? "#9d7007" : "#f7c041",
        eggshell: isDark ? "#1D1A0B" : "#F0ebd7"
    };

    // Met à jour le contenu texte des éléments <p> correspondant à chaque couleur
    for (const [id, hex] of Object.entries(colors)) {
        const p = document.getElementById(id);
        if (p) p.textContent = hex; // Affiche le code hexadécimal dans l’élément
    }
};

// Appelle la fonction dès le chargement de la page
updateColorCodes();

// Détecte les changements de thème dynamiques (ex. via un bouton de bascule)
const observer = new MutationObserver(updateColorCodes);

// Observe le changement de l’attribut 'data-theme' sur <html>
observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["data-theme"]
});
