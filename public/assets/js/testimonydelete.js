let links = document.querySelectorAll(".delete-testimony");

for (let link of links) {
    link.addEventListener("click", function (e) {
        e.preventDefault();
        if (confirm("Voulez-vous supprimer ce message ?")) {
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
                    // Redirection vers la liste des témoignages
                    window.location.href = '/admin/testimony';
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
