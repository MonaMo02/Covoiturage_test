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
const apiKey = 'pk.3be9e33b005e35c714ca37c18e918f08';

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
fetch(`https://us1.locationiq.com/v1/nearby.php?key=pk.b0202a65e13c7e35245ce1f7c6c65d84&lat=${latitude}&lon=${longitude}&radius=1000`)
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
        const ul = document.createElement('ul');

        filteredTrajets.forEach(trajet => {
            const li = document.createElement('li');
            const destinationInfo = `choix_trajet=${encodeURIComponent(trajet.id)}&depart=${encodeURIComponent(trajet.lieu_depart)}&destination=${encodeURIComponent(trajet.destination)}&date=${encodeURIComponent(trajet.date)}&time=${encodeURIComponent(trajet.heure_dep)}&idtrajet=${encodeURIComponent(trajet.id)}`;

// Create a clickable link
            const link = document.createElement('a');
            link.href = `reserver_trajet.php?${destinationInfo}`;
            // link.textContent = "Click to reserve";
                link.textContent =`depart:${trajet.lieu_depart}\n
                                destination:${trajet.destination}\n
                                date:${trajet.date}\n
                                trajet:${trajet.heure_dep}\n
                                        `

            // Append the link to the list item
            li.appendChild(link);

            ul.appendChild(li);
        });

        resultsContainer.appendChild(ul);
        document.getElementById('autocomplete-suggestions-location').innerHTML = '';
        document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
    } else {
        resultsContainer.innerHTML = '<p>No matching trajets found.</p>';
        document.getElementById('autocomplete-suggestions-location').innerHTML = '';
        document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
    }
}

