$(document).ready(function() {
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

                // Afficher les véhicules de la catégorie sélectionnée (s'il y en a)
                let selectedCategory = $('#filter-form select[name="category"]').val();
                let selectedPrice = parseInt($('#filter-form input[name="price"]').val());
                let selectedYear = parseInt($('#filter-form select[name="year"]').val()) || 0;
                let selectedKm = parseInt($('#filter-form input[name="kilometer"]').val()) || 0;

                let hasResults = false; // Variable pour indiquer s'il y a des résultats
                
            if (selectedCategory !== '') {
                let categoryVehicles = categories[selectedCategory];
                if (categoryVehicles) {
                    let categoryContainer = $('<div class="container category-container" id="category-' + selectedCategory + '">');
                    categoryContainer.append('<div class="row"><div class="col-12"><h2 class="category-title">' + selectedCategory + '</h2></div></div>');
                    let row = $('<div class="row">');
                    $.each(categoryVehicles, function(i, vehicle) {
                        let date = new Date(vehicle.year.date);
                        let year = date.getFullYear();
                        if (vehicle.price <= selectedPrice && (selectedYear === 0 || year >= selectedYear) && (selectedKm === 0 || vehicle.kilometer <= selectedKm)) {
                            let vehicleCard = `
                                <div class="col-lg-4 col-md-6 mb-4 vehicle">
                                    <div class="card h-100">
                                        <img class="card-img-top" src="${vehicle.image}" alt="${vehicle.brand}" class="zoom-card-img card-img-top img-fluid">
                                        <div class="card-body">
                                            <h4 class="card-title">${vehicle.brand}</h4>
                                            <p class="card-text">${vehicle.kilometer} km</p>
                                            <p>Année : </p>
                                            <p class="card-text">${year}</p>
                                        </div>
                                        <div class="card-footer">
                                        <p>Prix : </p>
                                            <small class="text-muted">${vehicle.price}€</small>
                                            <a href="/vehicle/${vehicle.slug}" class="btn btn2">Détail véhicule</a>
                                            <a class="btn mt-3" href="/contact/${vehicle.id}">Contact</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            row.append(vehicleCard);
                            hasResults = true; // Indiquer qu'il y a des résultats
                        }
                    });
                    categoryContainer.append(row);
                    $('#vehicles-container').append(categoryContainer);
                }
            } else {
                // Afficher les véhicules de toutes les catégories
                $.each(categories, function(categoryId, vehicles) {
                    let categoryContainer = $('<div class="container category-container" id="category-' + categoryId + '">');
                    categoryContainer.append('<div class="row"><div class="col-12"><h2 class="category-title">' + categoryId + '</h2></div></div>');
                    let row = $('<div class="row">');
                    $.each(vehicles, function(i, vehicle) {
                        let date = new Date(vehicle.year.date);
                        let year = date.getFullYear();
                        if (vehicle.price <= selectedPrice && (selectedYear === 0 || year >= selectedYear) && (selectedKm === 0 || vehicle.kilometer <= selectedKm)) {
                            
                            let vehicleCard = `
                                <div class="col-lg-4 col-md-6 mb-4 vehicle">
                                    <div class="card h-100">
                                        <img class="card-img-top" src="${vehicle.image}" alt="${vehicle.brand}" class="zoom-card-img card-img-top img-fluid">
                                        <div class="card-body">
                                            <h4 class="card-title">${vehicle.brand}</h4>
                                            <p class="card-text">${vehicle.kilometer}km</p>
                                            <p>Année : </p>
                                            <p class="card-text">${year}</p>
                                        </div>
                                        <div class="card-footer">
                                        <p>Prix : </p>
                                            <small class="text-muted">${vehicle.price}€</small>
                                            <a href="/vehicle/${vehicle.slug}" class="btn btn2">Détail véhicule</a>
                                            <a class="btn mt-3" href="/contact/${vehicle.id}">Contact</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                           
                            row.append(vehicleCard);
                            hasResults = true; // Indiquer qu'il y a des résultats
                            
                        }
                    });
                    categoryContainer.append(row);
                    $('#vehicles-container').append(categoryContainer);
                });
            }
            if (!hasResults) {
                $('#vehicles-container').append('<h3 class="no-results text-center">Aucun véhicule trouvé.</h3>');
            }
        }
    });

    $('#vehicles-container').empty().find('.no-results').remove();
}

// Événement lors de la modification de la catégorie dans le formulaire
$('#filter-form select, #filter-form input').on('change', function() {
    performSearch();
});

// Vérifier si une recherche est déjà effectuée lors du chargement de la page
if ($('#filter-form select, #filter-form input').val() !== '') {
    performSearch();
}

// Gérer le cas où l'utilisateur revient à "Toutes les catégories"
//$('#filter-form select[name="category"]').on('change', function() {
//    if ($(this).val() === '') {
//        // Recharger la page pour afficher les catégories et les véhicules initiaux
//        location.reload();
//    }
//});

// Gérer les dates de mise en circulation
$('#time-select').on('change', function() {
    let year = $(this).val();
    // Afficher simplement l'année sélectionnée
    $('.year-labels .min-year').text(year);
    // Appeler performSearch pour mettre à jour les véhicules affichés
    performSearch();
});

// Événement lors de la modification de la barre de sélection du prix
$('#price-slider').on('input', function() {
    let price = $(this).val();
    $('.price-labels .min-price').text(price !== '0' ? price + ' €' : '0 €');
});

// Événement lors de la modification de la barre de sélection du kilométrage
$('#km-slider').on('input', function() {
    let km = $(this).val();
    $('.km-labels .min-km').text(km !== '0' ? km + 'kilometer' : 'Tous les km');
});

$('button[name="remove"]').on('click', function(e) {
    e.preventDefault(); // Empêche l'envoi du formulaire (et donc le rafraîchissement de la page)
    
    // Réinitialise chaque champ du formulaire
    $('#filter-form select[name="category"]').val('');
    $('#price-slider').val(100000);
    $('#time-select').val('');
    $('#km-slider').val(400000);

    // Réaffiche tous les véhicules
    performSearch();
});


});