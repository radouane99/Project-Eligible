// Fonction pour initialiser la carte Leaflet
function initMap() {
    var map = L.map('map').setView([46.603354, 1.888334], 6); // Coordonnées du centre de la France et niveau de zoom 6

    // Ajouter une couche de tuiles OpenStreetMap à la carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ajouter un marqueur à la carte
    L.marker([51.5, -0.09]).addTo(map)
        .openPopup();
}

// Fonction pour mettre à jour la liste des villes
document.getElementById('codePostal').addEventListener('input', function() {
    // Appeler la fonction pour mettre à jour les villes lorsque le client commence à saisir dans le champ de code postal
    updateCities();
});

// Fonction pour mettre à jour les villes en fonction du code postal saisi par le client
function updateCities() {
    var codePostal = document.getElementById('codePostal').value;
    var xhr = new XMLHttpRequest();
    var url = '../src/get_ville_ByCode.php?codePostal=' + codePostal;

    xhr.open('GET', url, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var cities = JSON.parse(xhr.responseText);
            var villeSelect = document.getElementById('ville');
            villeSelect.innerHTML = '';
            cities.forEach(function(city) {
                var option = document.createElement('option');
                option.value = city;
                option.text = city;
                villeSelect.appendChild(option);
            });
        } else {
            alert('Erreur lors de la récupération des villes.');
        }
    };
    xhr.send();
}

// Événements à attacher lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser la carte Leaflet
    initMap();
 });






// Fonction pour remplir la liste déroulante avec les suggestions de voie
function fillStreetSuggestions(streets) {
    var streetDropdown = document.getElementById('streetSuggestions');
    streetDropdown.innerHTML = ''; // Effacer les options précédentes
    
    // Ajouter une option pour chaque rue suggérée
    streets.forEach(function(street) {
        var option = document.createElement('option');
        option.value = street;
        streetDropdown.appendChild(option);
    });
    
    // Afficher la liste déroulante si elle contient des suggestions, sinon la cacher
    streetDropdown.style.display = streets.length > 0 ? 'block' : 'none';
}

document.getElementById('nomVoie').addEventListener('input', function() {
    // Récupérer le code postal saisi
    var codePostal = document.getElementById('codePostal').value;
    // Récupérer le début du nom de voie saisi
    var streetStart = document.getElementById('nomVoie').value;
    
    // Appeler la fonction pour récupérer les suggestions de voie
    fetchStreetSuggestions(codePostal, streetStart);
});

// Fonction pour récupérer les suggestions de voie en fonction du code postal et du début du nom de voie
function fetchStreetSuggestions(codePostal, streetStart) {
    var xhr = new XMLHttpRequest();
    var url = '../src/get_ville_ByCode.php?codePostal=' + codePostal + '&street=' + streetStart;
    
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the response as JSON
                var response = JSON.parse(xhr.responseText);
                // Si la réponse contient des données
                if (response.length > 0) {
                    // Récupérer les rues et les identifiants de voie à partir de la réponse
                    var streets = [];
                    var numVoies = [];
                    for (var i = 0; i < response.length; i++) {
                        if (response[i].street) {
                            streets.push(response[i].street);
                        }
                        if (response[i].idway) {
                            numVoies.push(response[i].idway);
                        }
                    }
                    // Remplir les listes déroulantes avec les suggestions de voie et les identifiants de voie
                    fillStreetSuggestions(streets, 'streetSuggestions');
                    fillStreetSuggestions(numVoies, 'numVoieSuggestions');
                } else {
                    console.error('No street data found:', response);
                }
            } else {
                console.error('Error fetching street suggestions:', xhr.status);
            }
        }
    };
    xhr.send();
}

// Fonction pour remplir une liste déroulante avec les suggestions de voie ou les identifiants de voie
function fillStreetSuggestions(suggestions, dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    dropdown.innerHTML = '';

    // Ajouter chaque suggestion comme une option dans la liste déroulante
    suggestions.forEach(function(suggestion) {
        var option = document.createElement('option');
        option.value = suggestion;
        option.text = suggestion;
        dropdown.appendChild(option);
    });

    // Afficher la liste déroulante
    dropdown.style.display = 'block';
}

document.getElementById('streetSuggestions').addEventListener('change', function() {
    // Récupérer la valeur sélectionnée dans la liste déroulante
    var selectedStreet = this.value;
    
    // Mettre à jour le champ d'entrée avec la valeur sélectionnée
    document.getElementById('nomVoie').value = selectedStreet;
});
document.getElementById('numVoieSuggestions').addEventListener('change', function() {
    // Récupérer la valeur sélectionnée dans la liste déroulante
    var selectedNUmStreet = this.value;
    
    // Mettre à jour le champ d'entrée avec la valeur sélectionnée
    document.getElementById('numVoie').value = selectedNUmStreet;
});


   // Ajouter un écouteur d'événements de changement à la liste déroulante "N° voie"
   document.getElementById('numVoieSuggestions').addEventListener('change', function() {
    // Récupérer la valeur sélectionnée dans la liste déroulante "N° voie"
    var selectedNumVoie = this.value;
    
    // Remplir le champ d'entrée "N° Voie" avec la valeur sélectionnée
    document.getElementById('numVoie').value = selectedNumVoie;
});



function initializeMap(latitude, longitude) {
    // Initialiser la carte
    var map = L.map('maps').setView([latitude, longitude], 13);

    // Ajouter une couche de tuiles OpenStreetMap à la carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ajouter un marqueur à la position des coordonnées géographiques
    L.marker([latitude, longitude]).addTo(map)
        .bindPopup('Votre position').openPopup();
}

// Function to toggle the display of a section
function toggleSection(button) {
    var section = button.closest('h2').nextElementSibling;
    section.style.display = section.style.display === 'none' ? 'table' : 'none';
    button.textContent = section.style.display === 'none' ? 'Afficher' : 'Masquer';
}

// Function to toggle the display of all sections
function toggleAllSections(button) {
    var action = button.dataset.action;
    var sectionTables = document.querySelectorAll('.section-table');

    sectionTables.forEach(function(table) {
        table.style.display = action === 'show' ? 'table' : 'none';
    });

    button.textContent = action === 'show' ? 'Tout réduire' : 'Tout afficher';
    button.dataset.action = action === 'show' ? 'hide' : 'show';
}

// Add event listeners to toggle buttons
document.querySelectorAll('.toggle-section').forEach(function(button) {
    button.addEventListener('click', function() {
        toggleSection(button);
    });
});

document.querySelectorAll('.toggle-all').forEach(function(button) {
    button.addEventListener('click', function() {
        toggleAllSections(button);
    });
});
