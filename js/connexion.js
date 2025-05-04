// Fonction pour basculer la visibilité du champ mot de passe
function toggleVisibility() {
    const passwordField = document.getElementById("password");
    // Change le type de champ entre 'password' et 'text' pour afficher ou masquer le mot de passe
    const type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
}

// Lorsque le document est entièrement chargé
document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");

    // Gestion de la soumission du formulaire de connexion
    loginForm.addEventListener("submit", function(e) {
        const email = document.getElementById("email").value.trim(); // Récupère et nettoie l'email
        const password = document.getElementById("password").value; // Récupère le mot de passe

        // Expression régulière pour valider l'adresse email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Vérifie si les champs sont vides
        if (email === "" || password === "") {
            alert("Tous les champs doivent être remplis.");
            e.preventDefault(); // Empêche l'envoi du formulaire
            return;
        }

        // Vérifie si l'adresse email est valide
        if (!emailRegex.test(email)) {
            alert("Adresse email invalide.");
            e.preventDefault(); // Empêche l'envoi du formulaire
            return;
        }
    });

    // Récupère l'élément contenant un message éventuel depuis le serveur
    const messageDiv = document.getElementById("login-message");
    const status = messageDiv.dataset.status; // Lit la valeur du data-status

    // Affiche un message en fonction du statut retourné
    if (status === "banni") {
        alert("Votre compte a été banni.");
        window.location.href = "index.php"; // Redirige l'utilisateur vers la page d'accueil
    } else if (status === "invalide") {
        alert("Identifiant invalide");
    }
});
