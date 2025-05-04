// Sélectionne tous les formulaires ayant la classe 'change-form'
document.querySelectorAll('.change-form').forEach(form => {

    // Ajoute un écouteur d'événement sur la soumission du formulaire
    form.addEventListener('submit', function(event) {
        // Empêche le comportement par défaut du formulaire (rechargement de la page)
        event.preventDefault();

        // Sélectionne le bouton de soumission dans le formulaire
        const submitButton = form.querySelector('button[type="submit"]');
        // Désactive le bouton pour éviter les soumissions multiples
        submitButton.disabled = true;

        // Crée un objet FormData avec les données du formulaire
        const formData = new FormData(form);
        // Ajoute une donnée supplémentaire à envoyer avec le formulaire
        formData.append('maj_role', '1');

        // Envoie les données du formulaire à 'admin.php' via une requête POST
        fetch('admin.php', {
            method: 'POST',
            body: formData
        })
            // Vérifie si la réponse est correcte
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.text(); // Convertit la réponse en texte
            })
            // Traite les données reçues après une réponse réussie
            .then(data => {
                alert('Modification réussie !'); // Affiche un message de succès
            })
            // Gère les erreurs éventuelles durant la requête
            .catch(error => {
                console.error('Erreur:', error);
                alert('Échec de la modification'); // Affiche un message d'erreur
            })
            // Réactive le bouton de soumission après un délai de 5 secondes
            .finally(() => {
                setTimeout(() => {
                    submitButton.disabled = false;
                }, 5000);
            });
    });
});
