let deleteLinks = document.querySelectorAll("[data-delete]");

for (let link of deleteLinks) {
    link.addEventListener("click", function (e) {
        e.preventDefault();

        let dataType = this.dataset.type;
        let confirmMessage = dataType === "vehicle" ? "Voulez-vous supprimer ce véhicule ?" : "Voulez-vous supprimer cette image ?";
        let route = dataType === "vehicle" ? "/admin/vehicle" : "";

        if (confirm(confirmMessage)) {
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

                    if (dataType === "vehicle") {
                        // Afficher le message flash
                        alert(data.message);
                        // Redirection vers la route 'admin_dishes_index'
                        window.location.href = route;
                    }
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
