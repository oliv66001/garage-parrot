document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', e => {
        if (!confirm('Voulez-vous supprimer ce message ?')) {
            e.preventDefault();
        }
    });
});
