let emailInitial;
let nomInitial;

function enableNomEditing() { //permet l'écriture dans la zone d'écriture du nom
    document.getElementById('nom').removeAttribute('readonly');
    document.getElementById('nom-edit-buttons').style.display = 'inline';
    document.getElementById('nom-modify-button').style.display = 'none';
}

function cancelNomEditing() {//annule l'écriture dans la zone d'écriture du nom
    document.getElementById('nom').setAttribute('readonly', true);
    document.getElementById('nom-edit-buttons').style.display = 'none';
    document.getElementById('nom-modify-button').style.display = 'inline';
    document.getElementById('nom').value = nomInitial;
}

function enableEmailEditing() {//permet l'écriture dans la zone d'écriture de l'email
    document.getElementById('email').removeAttribute('readonly');
    document.getElementById('email-edit-buttons').style.display = 'inline';
    document.getElementById('email-modify-button').style.display = 'none';
}

function cancelEmailEditing() {//annule l'écriture dans la zone d'écriture de l'email
    document.getElementById('email').setAttribute('readonly', true);
    document.getElementById('email-edit-buttons').style.display = 'none';
    document.getElementById('email-modify-button').style.display = 'inline';
    document.getElementById('email').value = emailInitial;
}

document.addEventListener('DOMContentLoaded', () => {
    //récupère les valeurs du formulaire de profil
    const profileForm = document.getElementById('profile-form');

    // Initialiser les variables avec les valeurs du DOM
    emailInitial = document.getElementById('email').value.trim();
    nomInitial = document.getElementById('nom').value.trim();

    //cas du bouton de type submit
    profileForm.addEventListener('submit', function (event) {
        event.preventDefault();


        document.getElementById('loading-spinner').style.display = 'block'; // Affiche l'image

        setTimeout(() => {
            //récupère l'email
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                alert('Veuillez entrer une adresse e-mail valide.');
                emailInput.focus();
                document.getElementById('loading-spinner').style.display = 'none';
                return;
            }

            const formData = new FormData(profileForm);
            //envoi des informations au PHP
            fetch('profile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    //si cela n'as pas marché
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.text();
                })
                .then(data => {
                    alert('Modification enregistrée avec succès.');
                    //modifications des informations pour au cas où il y a une annulation la prochaine fois
                    nomInitial = document.getElementById('nom').value.trim();
                    emailInitial = document.getElementById('email').value.trim();

                    //remet en lecture seule la zone de texte
                    document.getElementById("nom").setAttribute('readonly', true);
                    document.getElementById('email').setAttribute('readonly', true);
                    //faire disparaitre les cases d'annulation et confirmation et remet modification
                    document.getElementById('nom-edit-buttons').style.display = 'none';
                    document.getElementById('email-edit-buttons').style.display = 'none';
                    document.getElementById('nom-modify-button').style.display = 'inline';
                    document.getElementById('email-modify-button').style.display = 'inline';
                })
                .catch(error => {
                    //cas où il y a une erreur
                    console.error('Erreur :', error);
                    alert('Échec de la modification');
                })
                .finally(() =>{
                    document.getElementById('loading-spinner').style.display = 'none'; // Cache l'image après traitement
                });

        }, 3000);
    });

    document.querySelectorAll('.delete-voyage-form').forEach(form => { //Pour chaque formulaire de suppression de voyage, empêche l'envoi par défaut
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(form);
            //Envoi les informations au PHP
            fetch('profile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.text();
                })
                .then(data => {
                    alert('Voyage annulé avec succès.');
                    //supprime la ligne du voyage annulé
                    const row = form.closest('tr');
                    if (row) row.remove();
                    //vérifie le nombre de voyage
                    const table = document.getElementById('voyages-table');
                    const remainingRows = table.querySelectorAll('tr').length - 1; // exclude header
                    //cas où le nombre de voyage est à 0
                    if (remainingRows === 0) {
                        const container = document.getElementById('voyages-container');
                        if (container) container.remove();

                        const message = document.createElement('p');
                        message.textContent = "Aucun voyage sélectionné.";
                        message.id = "no-voyage-message";
                        document.querySelector('.profil').appendChild(message);
                    }
                    document.getElementById('loading-spinner').style.display = 'none';
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    alert('Échec de l’annulation');
                });
        });
    });
});
