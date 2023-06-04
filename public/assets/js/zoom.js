$(document).ready(function() {
    // Fonction pour effectuer la recherche
    function performSearch() {
        $.ajax({
            url: $('#filter-form').attr('action'),
            data: $('#filter-form').serialize(),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Vider le conteneur des résultats
                $('#vehicles-container').empty();

                // Parcourir les données renvoyées et trier les véhicules par catégorie
                let categories = {};
                $.each(data, function(categoryId, vehicles) {
                    // Créer un tableau pour chaque catégorie
                    if (!categories[categoryId]) {
                        categories[categoryId] = [];
                    }

                    // Ajouter les véhicules à leur catégorie respective
                    $.each(vehicles, function(i, vehicle) {
                        categories[categoryId].push(vehicle);
                    });
                });

                // Parcourir les catégories et afficher les véhicules dans chaque catégorie
                $.each(categories, function(categoryId, vehicles) {
                    let categoryContainer = $('<div class="container category-container" id="category-' + categoryId + '">');
                    categoryContainer.append('<div class="row"><div class="col-12"><h2 class="category-title">' + categoryId + '</h2></div></div>');
                    let row = $('<div class="row">');

                    // Ajouter chaque véhicule de la catégorie à la ligne
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
                                        <a href="${vehicle.slug}" class="btn btn2">Détail véhicule</a>
                                    </div>
                                </div>
                            </div>
                        `;

                        row.append(vehicleCard);
                    });

                    // Ajouter la ligne de véhicules à la catégorie
                    categoryContainer.append(row);
                    $('#vehicles-container').append(categoryContainer);
                });

                // Afficher un message lorsque aucun résultat n'est trouvé
                if ($.isEmptyObject(categories)) {
                    $('#vehicles-container').append('<p>Aucun véhicule correspondant aux critères de recherche n\'a été trouvé.</p>');
                }
            }
        });
    }

    // Événement lors de la modification de la catégorie dans le formulaire
    $('#filter-form select, #filter-form input').on('change', function() {
        performSearch();
    });

    // Vérifier si une recherche est déjà effectuée lors du chargement de la page
    if ($('#filter-form select, #filter-form input').val() !== '') {
        performSearch();
    }
});


    // Afficher un message lorsque aucun résultat n'est trouvé
    if (!hasResults) {
        $('#vehicles-container').append('<p>Aucun véhicule correspondant aux critères de recherche n\'a été trouvé.</p>');
    
    // Afficher le titre "Résultats de la recherche" si des résultats sont disponibles
    if (hasResults) {
        $('#vehicles-container').prepend('<div class="container category-container"><div class="row"><div class="col-12"><h2 class="category-title">Résultats de la recherche</h2></div></div></div>');
    } else {
        $('#vehicles-container').find('.category-container:first').hide();
    }
    
        // Gérer le cas où l'utilisateur revient à "Toutes les catégories"
    $('#filter-form select[name="category"]').on('change', function() {
    if ($(this).val() === '') {
        // Recharger la page pour afficher les catégories et les véhicules initiaux
        location.reload();
    }
    });
    };


//Price change

$(document).ready(function() {
    // Événement lors de la modification de la barre de sélection du prix
    $('#price-slider').on('input', function() {
        let price = $(this).val();
        $('.price-labels .min-price').text(price !== '0' ? price + ' €' : 'Tous les prix');
    });
});


// Générer les options de l'année
function generateYearOptions(startYear, endYear) {
    var select = document.getElementById('vehicle_year');
    select.innerHTML = ''; // Réinitialiser les options existantes

    for (var year = startYear; year <= endYear; year++) {
        var option = document.createElement('option');
        option.value = year;
        option.text = year;
        select.appendChild(option);
    }
}

// Appeler la fonction de génération d'options au chargement de la page
window.onload = function() {
    generateYearOptions(1960, 2099);
};

  
