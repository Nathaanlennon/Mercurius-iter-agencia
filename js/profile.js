let emailInitial;
let nomInitial;

function enableNomEditing() {
    console.log(nomInitial);
    document.getElementById('nom').removeAttribute('readonly');
    document.getElementById('nom-edit-buttons').style.display = 'inline';
    document.getElementById('nom-modify-button').style.display = 'none';
    console.log(nomInitial);
}

function cancelNomEditing() {
    console.log(nomInitial);
    document.getElementById('nom').setAttribute('readonly', true);
    document.getElementById('nom-edit-buttons').style.display = 'none';
    document.getElementById('nom-modify-button').style.display = 'inline';
    document.getElementById('nom').value = nomInitial;
    console.log(nomInitial);
}

function enableEmailEditing() {
    console.log(emailInitial);
    document.getElementById('email').removeAttribute('readonly');
    document.getElementById('email-edit-buttons').style.display = 'inline';
    document.getElementById('email-modify-button').style.display = 'none';
    console.log(emailInitial);
}

function cancelEmailEditing() {
    console.log(emailInitial);
    document.getElementById('email').setAttribute('readonly', true);
    document.getElementById('email-edit-buttons').style.display = 'none';
    document.getElementById('email-modify-button').style.display = 'inline';
    document.getElementById('email').value = emailInitial;
    console.log(emailInitial);
}

document.addEventListener('DOMContentLoaded', () => {
    const profileForm = document.getElementById('profile-form');

    // Initialiser les variables avec les valeurs du DOM
    emailInitial = document.getElementById('email').value.trim();
    nomInitial = document.getElementById('nom').value.trim();

    profileForm.addEventListener('submit', function (event) {
        event.preventDefault();

        document.getElementById('loading-spinner').style.display = 'block'; // Affiche l'image

        setTimeout(() => {
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

            fetch('profile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.text();
                })
                .then(data => {
                    alert('Modification enregistrée avec succès.');

                    nomInitial = document.getElementById('nom').value.trim();
                    emailInitial = document.getElementById('email').value.trim();

                    document.getElementById("nom").setAttribute('readonly', true);
                    document.getElementById('email').setAttribute('readonly', true);

                    document.getElementById('nom-edit-buttons').style.display = 'none';
                    document.getElementById('email-edit-buttons').style.display = 'none';
                    document.getElementById('nom-modify-button').style.display = 'inline';
                    document.getElementById('email-modify-button').style.display = 'inline';
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    alert('Échec de la modification');
                })
                .finally(() => {
                    document.getElementById('loading-spinner').style.display = 'none'; // Cache l'image après traitement
                });

        }, 3000);
    });

    document.querySelectorAll('.delete-voyage-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(form);

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

                    const row = form.closest('tr');
                    if (row) row.remove();

                    const table = document.getElementById('voyages-table');
                    const remainingRows = table.querySelectorAll('tr').length - 1; // exclude header

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
