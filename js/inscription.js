// Fonction pour afficher/masquer un champ de type mot de passe
function toggleVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    // Change le type du champ entre 'password' et 'text'
    field.type = field.type === "password" ? "text" : "password";
}

// Exécute le code une fois que le DOM est complètement chargé
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formInscription");

    // Gestion de la soumission du formulaire d'inscription
    form.addEventListener("submit", function (e) {
        // Récupère les valeurs des champs et les nettoie
        const nom = document.getElementById("nom").value.trim();
        const prenom = document.getElementById("prenom").value.trim();
        const email = document.getElementById("email").value.trim();
        const emailConfirm = document.getElementById("email_confirm").value.trim();
        const password = document.getElementById("password").value;
        const mdpConfirm = document.getElementById("mdp_confirm").value;

        // Expression régulière pour valider l'adresse email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Vérifie si tous les champs sont remplis
        if (!nom || !prenom || !email || !emailConfirm || !password || !mdpConfirm) {
            alert("Tous les champs doivent être remplis.");
            e.preventDefault(); // Empêche l'envoi du formulaire
            return;
        }

        // Vérifie si l'adresse email est valide
        if (!emailRegex.test(email)) {
            alert("L'adresse email n'est pas valide.");
            e.preventDefault(); // Empêche l'envoi du formulaire
            return;
        }

        // (Optionnel : vous pourriez aussi valider ici l’égalité des emails et mots de passe avant soumission)
    });

    // Affiche un message selon l'état du serveur (erreur d'inscription)
    const messageDiv = document.getElementById("signup-message");
    const status = messageDiv.dataset.status;

    if (status === "email_mismatch") {
        alert("Les emails ne correspondent pas !");
    } else if (status === "password_mismatch") {
        alert("Les mots de passe ne correspondent pas !");
    } else if (status === "email_exists") {
        alert("Cet email est déjà utilisé !");
    }
});
