function toggleVisibility() {
    const passwordField = document.getElementById("password");
    const type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
}

document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");

    // Gestion du formulaire
    loginForm.addEventListener("submit", function(e) {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === "" || password === "") {
            alert("Tous les champs doivent être remplis.");
            e.preventDefault();
            return;
        }

        if (!emailRegex.test(email)) {
            alert("Adresse email invalide.");
            e.preventDefault();
            return;
        }
    });

    // Affichage du message serveur
    const messageDiv = document.getElementById("login-message");
    const status = messageDiv.dataset.status;

    if (status === "banni") {
        alert("Votre compte a été banni.");
        window.location.href = "index.php";
    } else if (status === "invalide") {
        alert("Identifiant invalide");
    }
});
