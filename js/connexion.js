function toggleVisibility() {//fonction pour la visibilité du mot de passe
    const passwordField = document.getElementById("password");// on récupère le texte
    const type = passwordField.type === "password" ? "text" : "password";// transformation en texte ou password selon ce que c'était
    passwordField.type = type;
}

document.addEventListener("DOMContentLoaded", () => {
    //récupération du formulaire de connexion
    const loginForm = document.getElementById("loginForm");

    // Gestion du formulaire
    loginForm.addEventListener("submit", function(e) {//se déclenche lors du clic sur le bouton de type submit
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
