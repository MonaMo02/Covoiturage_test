var jsonData = document.getElementById("db").value;

// Throttle function to limit requests
function throttle(fn, delay) {
    let lastCall = 0;
    return function (...args) {
        const now = new Date().getTime();
        if (now - lastCall < delay) {
            return;
        }
        lastCall = now;
        fn(...args);
    };
}

const throttledAutocompleteLocation = throttle(fetchAutocompleteSuggestions, 500); // 2 requests per second
const throttledAutocompleteDestination = throttle(fetchAutocompleteSuggestions, 500); // 2 requests per second

document.getElementById('location').addEventListener('input', function () {
    const query = this.value;
    if (query.trim() !== '') {
        throttledAutocompleteLocation(query, 'location');
    } else {
        document.getElementById('autocomplete-suggestions-location').innerHTML = '';
    }
});

document.getElementById('destination').addEventListener('input', function () {
    const query = this.value;
    if (query.trim() !== '') {
        throttledAutocompleteDestination(query, 'destination');
    } else {
        document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
    }
});
function findTrajets() {
try {
    var parsedData = JSON.parse(jsonData);
const departureInput = document.getElementById('location').value;
const destinationInput = document.getElementById('destination').value;
const timeInput = document.getElementById('time').value;
const dateInput = document.getElementById('date').value;
const departureLatitude=document.getElementById('location-lat').value;
const departureLongitude=document.getElementById('location-lon').value;


    findNearbyPlaces(departureLatitude, departureLongitude, parsedData, destinationInput, timeInput, dateInput);


// Clear suggestion lists
document.getElementById('autocomplete-suggestions-location').innerHTML = '';
document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
} catch (error) {
console.error('Error in findTrajets:', error);
}
}

function fetchAutocompleteSuggestions(query, inputId) {
const apiKey = 'pk.3be9e33b005e35c714ca37c18e918f08';  /*****INSEREZ VOTRE PROPRE APIKEY ICI*****/

fetch(`https://us1.locationiq.com/v1/autocomplete.php?key=${apiKey}&q=${encodeURIComponent(query)}`)
.then(response => response.json())
.then(data => {
    handleAutocompleteSuggestions(inputId, data);
    console.log('Autocomplete API Response:', data);
})
.catch(error => console.error('Autocomplete request failed:', error));
}

function handleAutocompleteSuggestions(inputId, suggestions) {
const autocompleteSuggestions = document.getElementById(`autocomplete-suggestions-${inputId}`);
autocompleteSuggestions.innerHTML = '';

suggestions.forEach(suggestion => {
const suggestionItem = document.createElement('div');
suggestionItem.textContent = suggestion.display_name;
suggestionItem.addEventListener('click', () => {
    document.getElementById(inputId).value = suggestion.display_name;

    // Trim the coordinates
    const lat = suggestion.lat.trim();
    const lon = suggestion.lon.trim();
    
    // Update coordinates without leading/trailing spaces
    document.getElementById(`${inputId}-lat`).value = lat;
    document.getElementById(`${inputId}-lon`).value = lon;
    console.log(`Coordinates for ${suggestion.display_name}:lat:${suggestion.lat.trim()} lon:${suggestion.lon.trim()} `)

    // Log coordinates


    autocompleteSuggestions.innerHTML = '';
});
autocompleteSuggestions.appendChild(suggestionItem);
});
}


const precision = 7;

function findNearbyPlaces(latitude, longitude, trajets, destinationInput, timeInput, dateInput) {
// Call Nearby Places API to find nearby places around the inputted departure location
fetch(`https://us1.locationiq.com/v1/nearby.php?key=pk.b3131f0ea825db713292fcdae7328f5d&lat=${latitude}&lon=${longitude}&radius=1000`) //INSEREZ VOTRE PROPRE APIKEY ICI 
.then(response => response.json())
.then(data => {
    console.log('API Response:', data);
    filterTrajets(data, trajets, destinationInput, timeInput, dateInput);
})
.catch(error => console.error('Nearby places request failed:', error));
document.getElementById('autocomplete-suggestions-location').innerHTML = '';
document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
}

function filterTrajets(nearbyPlaces, trajets, destinationInput, timeInput, dateInput) {
console.log('Nearby Places:', nearbyPlaces);

const radius = 0.0005; // Adjust this value based on your tolerance

const destinationLatitude = parseFloat(document.getElementById('destination-lat').value);
const destinationLongitude = parseFloat(document.getElementById('destination-lon').value);

//console.log('destination:',destinationLatitude,destinationLongitude)
const filteredTrajets = trajets.filter(trajet => {
// Check if the trajet's departure is in the list of nearby places within the specified radius
const isNearby = nearbyPlaces.some(place => {
  //  console.log("nearby lat:", place.lat);
   // console.log("db lat:", trajet.locationlat);
   // console.log("nearby lon:", place.lon);
   // console.log("db lon:", trajet.locationlon);
    return (
        Math.abs(parseFloat(place.lat.trim()).toFixed(precision)) - parseFloat(trajet.locationlat.trim()).toFixed(precision) < radius &&
        Math.abs(parseFloat(place.lon.trim()).toFixed(precision)) - parseFloat(trajet.locationlon.trim()).toFixed(precision) < radius
    );
});


// Check additional conditions for destination, time, and date

const tolerance = 1e-6; // Set a small tolerance for floating-point comparison

// console.log('parsed lat', parseFloat(destinationLatitude.toFixed(precision)))
// console.log('my db lat',parseFloat(trajet.destinationlat.trim()).toFixed(precision))
// console.log('parsed lon', parseFloat(destinationLongitude.toFixed(precision)))
// console.log('my db lon',parseFloat(trajet.destinationlon.trim()).toFixed(precision))

const isMatchingDestination =
Math.abs(parseFloat(destinationLatitude.toFixed(precision)) - parseFloat(trajet.destinationlat.trim()).toFixed(precision)) < tolerance &&
Math.abs(parseFloat(destinationLongitude.toFixed(precision)) - parseFloat(trajet.destinationlon.trim()).toFixed(precision)) < tolerance;

const isMatchingTime = timeInput === trajet.heure_dep;
const isMatchingDate = dateInput === trajet.date;


// Log details for troubleshooting
console.log('Trajet:', trajet);
console.log('isNearby:', isNearby);
console.log('isMatchingDestination:', isMatchingDestination);
console.log('isMatchingTime:', isMatchingTime);
console.log('isMatchingDate:', isMatchingDate);

return isNearby && isMatchingDestination && isMatchingTime && isMatchingDate;
});

// Log the filteredTrajets for further inspection
//console.log('Filtered Trajets:', filteredTrajets);

displayResults(filteredTrajets);
document.getElementById('autocomplete-suggestions-location').innerHTML = '';
document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
}




function displayResults(filteredTrajets) {
    const resultsContainer = document.getElementById('results-container');
    resultsContainer.innerHTML = '';

    if (filteredTrajets.length > 0) {
        const div = document.createElement('div');
        div.className = 'journey-container-find';

        filteredTrajets.forEach(trajet => {
            const journeyItem = document.createElement('div');
            journeyItem.className = 'journey-item-find';


            // Location Pin
            const locationPinTop = document.createElement('i');
            locationPinTop.className = 'fa-solid fa-location-pin tp';
            const locationPinBottom = document.createElement('i');
            locationPinBottom.className = 'fa-solid fa-location-pin btm';

            // Journey Info
            const journeyInfo = document.createElement('div');
            journeyInfo.className = ' journey-info-find-find';

            // Info Section 1
            const infoSection1 = document.createElement('div');
            infoSection1.className = 'info-section';

            const infoLabel1 = document.createElement('span');
            infoLabel1.className = 'info-label';
            const infoValue1 = document.createElement('span');
            infoValue1.className = 'info-value';
            infoValue1.textContent = trimLocation(trajet.lieu_depart);

            // Info Section 2
            const infoSection3 = document.createElement('div');
            infoSection3.className = 'info-section-prix indexprice';

            const infoLabel3 = document.createElement('span');
            infoLabel3.className = 'info-label';
            const infoValue3 = document.createElement('span');
            infoValue3.className = 'info-value prix';
            infoValue3.textContent = trajet.prix;

            // Info Section 3
            const infoSection2 = document.createElement('div');
            infoSection2.className = 'info-section';
            infoSection2.style.marginTop="20px";

            const infoLabel2 = document.createElement('span');
            infoLabel2.className = 'info-label';
            const infoValue2 = document.createElement('span');
            infoValue2.className = 'info-value';
            infoValue2.textContent = trimLocation(trajet.destination);

            // Reserver Button
            const resButton = document.createElement('input');
            resButton.type = 'button';
            resButton.className = 'resbutton-find';
            resButton.value = 'Reserver';
            resButton.onclick = () => redirectToReserver(trajet); // Pass trajet as an argument

            const lineBreak = document.createElement('hr');

            // Append elements to the DOM
            infoSection1.appendChild(infoLabel1);
            infoSection1.appendChild(infoValue1);
            infoSection2.appendChild(infoLabel2);
            infoSection2.appendChild(infoValue2);
            infoSection3.appendChild(infoLabel3);
            infoSection3.appendChild(infoValue3);

            journeyInfo.appendChild(infoSection1);
            journeyInfo.appendChild(infoSection2);
            journeyInfo.appendChild(infoSection3);

            journeyItem.appendChild(locationPinTop);
            journeyItem.appendChild(journeyInfo);
            journeyItem.appendChild(locationPinBottom);
            journeyItem.appendChild(document.createElement('div')); // Divider
            journeyItem.appendChild(resButton);

            div.appendChild(journeyItem);
            div.appendChild(lineBreak);

        
        });

        resultsContainer.appendChild(div);
        div.appendChild(lineBreak);

        document.getElementById('autocomplete-suggestions-location').innerHTML = '';
        document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
    } else {
        resultsContainer.innerHTML = '<div class="alert alert-danger">Aucun trajet disponible pour le moment</div>';
        document.getElementById('autocomplete-suggestions-location').innerHTML = '';
        document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
    }
}

function trimLocation(location) {
    return location.split(",").slice(0, 2).join(",");
}

function redirectToReserver(trajet) {
    const destinationInfo = `choix_trajet=${encodeURIComponent(trajet.id)}&depart=${encodeURIComponent(trajet.lieu_depart)}&destination=${encodeURIComponent(trajet.destination)}&date=${encodeURIComponent(trajet.date)}&time=${encodeURIComponent(trajet.heure_dep)}&idtrajet=${encodeURIComponent(trajet.id)}`;
    window.location.href = `../trajet/reserver_trajet.php?${destinationInfo}`;
}
