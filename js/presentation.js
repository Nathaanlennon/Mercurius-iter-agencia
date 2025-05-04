
    const updateColorCodes = () => {
    const isDark = document.documentElement.getAttribute("data-theme") === "dark";

    const colors = {
    red: isDark ? "#660000" : "#9a0000",
    purple: isDark ? "#3d003d" : "#650065",
    yellow: isDark ? "#9d7007" : "#f7c041",
    eggshell: isDark ? "#1D1A0B" : "#F0ebd7"
};

    for (const [id, hex] of Object.entries(colors)) {
    const p = document.getElementById(id);
    if (p) p.textContent = hex;
}
};

    // Appelle la fonction au chargement
    updateColorCodes();

    // Optionnel : rechange si le thème change dynamiquement (ex : bouton toggle)
    const observer = new MutationObserver(updateColorCodes);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ["data-theme"] });

