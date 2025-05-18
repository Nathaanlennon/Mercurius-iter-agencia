// Cookie helpers
function setCookie(name, value, days) { //fonction créant un cookie
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 86400000)); // 24*60*60*1000
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) { //fonction pour récuperer la valeur d'un cookie selon le nom
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');//récupération de tout les cookies séparé par des ;
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim(); ///suppression des espaces inutiles
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
    }
    return null;
}

// Theme
window.addEventListener("DOMContentLoaded", () => {
    const currentTheme = getCookie("theme");
    if (currentTheme === "dark") { //si valeur du cookie est dark
        document.documentElement.setAttribute("data-theme", "dark");//ajoute d'un attribut dans une balise html pour le mode sombre
    }

    document.getElementById("theme-toggle").addEventListener("click", () => {//se déclenche lors du clic sur le bouton
        const isDark = document.documentElement.getAttribute("data-theme") === "dark"; //vérifie que le thème est dark
        document.documentElement.setAttribute("data-theme", isDark ? "light" : "dark"); //on passe à dark ou light en fonction du thème actuel
        setCookie("theme", isDark ? "light" : "dark", 30);//on met à jour le cookie
    });
});
