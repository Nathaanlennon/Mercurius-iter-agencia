// Active la modification du champ "nom"
function enableNomEditing() {
    document.getElementById('nom').removeAttribute('readonly'); // Permet la modification du champ
    document.getElementById('nom-edit-buttons').style.display = 'inline'; // Affiche les boutons "valider" et "annuler"
    document.getElementById('nom-modify-button').style.display = 'none'; // Cache le bouton "modifier"
}

// Annule la modification du champ "nom"
function cancelNomEditing() {
    document.getElementById('nom').setAttribute('readonly', true); // Rend le champ à nouveau non modifiable
    document.getElementById('nom-edit-buttons').style.display = 'none'; // Cache les boutons "valider" et "annuler"
    document.getElementById('nom-modify-button').style.display = 'inline'; // Réaffiche le bouton "modifier"
    document.getElementById('nom').value = nomInitial; // Restaure la valeur initiale
}

// Active la modification du champ "email"
function enableEmailEditing() {
    document.getElementById('email').removeAttribute('readonly'); // Permet la modification du champ
    document.getElementById('email-edit-buttons').style.display = 'inline'; // Affiche les boutons "valider" et "annuler"
    document.getElementById('email-modify-button').style.display = 'none'; // Cache le bouton "modifier"
}

// Annule la modification du champ "email"
function cancelEmailEditing() {
    document.getElementById('email').setAttribute('readonly', true); // Rend le champ à nouveau non modifiable
    document.getElementById('email-edit-buttons').style.display = 'none'; // Cache les boutons "valider" et "annuler"
    document.getElementById('email-modify-button').style.display = 'inline'; // Réaffiche le bouton "modifier"
    document.getElementById('email').value = emailInitial; // Restaure la valeur initiale
}
