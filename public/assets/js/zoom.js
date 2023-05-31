console.log("Le fichier zoom.js est chargé !");


$(document).ready(function() {
    // Fonction pour effectuer la recherche
    function performSearch() {
        console.log("La fonction performSearch est appelée !");
        $.ajax({
            url: $('#filter-form').attr('action'),
            data: $('#filter-form').serialize(),
            type: 'GET',
            dataType: 'json',
            
            success: function(data) {
                console.log('Data received:', data);

                // Vider chaque conteneur de catégorie
                $('.category-container').empty();

                // Parcourir les données renvoyées et ajouter chaque véhicule à son conteneur respectif
                let hasResults = false;
                $.each(data, function(categoryId, vehicles) {
                    let categoryContainer = $('#category-' + categoryId);
                    if (!categoryContainer.length) {
                        // Si le conteneur de cette catégorie n'existe pas encore, créez-le
                        categoryContainer = $('<div class="container category-container" id="category-' + categoryId + '">');
                        $('#vehicles-container').append(categoryContainer);
                    }
                    console.log(performSearch());

                    // Ajouter chaque véhicule de cette catégorie
                    $.each(vehicles, function(i, vehicle) {
                        console.log('Adding vehicle:', vehicle);
                        let vehicleCard = `
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100">
                                    <img class="card-img-top" src="${vehicle.image}" alt="${vehicle.brand}" class="zoom-card-img card-img-top img-fluid">
                                    <div class="card-body">
                                        <h4 class="card-title">${vehicle.brand}</h4>
                                        <p class="card-text">${vehicle.description}</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-muted">${vehicle.price}€</small>
                                        <a href="${vehicle.slug}" class="btn btn2">Detail véhicule</a>
                                    </div>
                                </div>
                            </div>
                        `;

                        categoryContainer.append(vehicleCard);
                        hasResults = true;
                    });
                });

                // Afficher un message lorsque aucun résultat n'est trouvé
                if (!hasResults) {
                    $('#vehicles-container').append('<p>Aucun véhicule correspondant aux critères de recherche n\'a été trouvé.</p>');
                }
            }
        });
    }

    // Événement lors de la modification de la catégorie dans le formulaire
    $('#filter-form select, #filter-form input').on('change', function() {
        performSearch();
    });
});
