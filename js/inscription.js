function toggleVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === "password" ? "text" : "password";
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formInscription");

    form.addEventListener("submit", function (e) {
        const nom = document.getElementById("nom").value.trim();
        const prenom = document.getElementById("prenom").value.trim();
        const email = document.getElementById("email").value.trim();
        const emailConfirm = document.getElementById("email_confirm").value.trim();
        const password = document.getElementById("password").value;
        const mdpConfirm = document.getElementById("mdp_confirm").value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!nom || !prenom || !email || !emailConfirm || !password || !mdpConfirm) {
            alert("Tous les champs doivent être remplis.");
            e.preventDefault();
            return;
        }

        if (!emailRegex.test(email)) {
            alert("L'adresse email n'est pas valide.");
            e.preventDefault();
            return;
        }
    });

    // Message serveur (erreur inscription)
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
