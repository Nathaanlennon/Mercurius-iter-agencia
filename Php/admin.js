document.querySelectorAll('.change-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const formData = new FormData(form);
        formData.append('maj_role', '1');

        fetch('admin.php', {
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
                    submitButton.disabled = false;
                }, 5000);
            });
    });
});
