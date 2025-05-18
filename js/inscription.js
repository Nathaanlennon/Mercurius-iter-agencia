function toggleVisibility(fieldId) { //fonction pour la visibilité du mot de passe
    const field = document.getElementById(fieldId); // on récupère le texte
    field.type = field.type === "password" ? "text" : "password";// transformation en texte ou password selon ce que c'était
}

document.addEventListener("DOMContentLoaded", () => {
    //on récupère les informations du formulaire d'inscription
    const form = document.getElementById("formInscription");

    // Liste des champs à filtrer
    const fieldsToFilter = ["nom", "prenom", "email", "email_confirm", "password", "mdp_confirm"];

    fieldsToFilter.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^a-zA-Z0-9@.]/g, "");
            });
        }
    });

    //se déclenche lors de l'appui du bouton de type submit
    form.addEventListener("submit", function (e) {

        //récupération des valeurs données par l'utilisateur
        const nom = document.getElementById("nom").value.trim();
        const prenom = document.getElementById("prenom").value.trim();
        const email = document.getElementById("email").value.trim();
        const emailConfirm = document.getElementById("email_confirm").value.trim();
        const password = document.getElementById("password").value;
        const mdpConfirm = document.getElementById("mdp_confirm").value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        //vérifie que les champs sont remplis
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
