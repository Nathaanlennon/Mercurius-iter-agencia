document.querySelectorAll('.change-form').forEach(form => {//choisis tous les formulaires dans la classe
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        //lorsqu'un submit est fait
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        //désactivation du bouton de modification
        const formData = new FormData(form);//récupération des données du formulaire
        formData.append('maj_role', '1');//permet au php d'identifier l'action

        fetch('admin.php', {//envoi de la requête
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.text();
            })
            .then(data => {
                alert('Modification réussie !');
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Échec de la modification');
            })
            .finally(() => {
                setTimeout(() => {
                    submitButton.disabled = false; //réactive le bouton après 5 sec
                }, 5000);
            });
    });
});
