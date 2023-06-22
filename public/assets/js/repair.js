let productLinks = document.querySelectorAll("[data-delete]");

for (let link of productLinks) {
    link.addEventListener("click", function (e) {
        e.preventDefault();
        if (confirm("Voulez-vous supprimer définitivement cette réparation ?")) {
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ "_token": this.dataset.token })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.parentElement.remove();

                      // Afficher le message flash
                    alert(data.message);
                    // Redirection vers la route 'admin_repair_index'
                    window.location.href = '/admin/reparations';
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la suppression:', error);
            });
        }
    });
}
