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
