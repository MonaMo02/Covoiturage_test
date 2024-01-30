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

function fetchAutocompleteSuggestions(query, inputId) {
const apiKey = 'pk.1e1d94098d9e8333fee0998d53e5deb4';

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

    // Log coordinates to the console
    console.log(`${inputId} Coordinates:`);
    console.log("Latitude:", lat);
    console.log("Longitude:", lon);

    autocompleteSuggestions.innerHTML = '';
});
autocompleteSuggestions.appendChild(suggestionItem);
});
}


const http = new XMLHttpRequest();
let result = document.querySelector("#result");

function getPos() {
return new Promise((resolve, reject) => {
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const bdcAPI = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${position.coords.latitude}&longitude=${position.coords.longitude}`;
            resolve({
                position: position.coords,
                bdcAPI: bdcAPI
            });
        },
        (err) => {
            alert(err.message);
        }
    );
} else {
    alert("Votre navigateur ne supporte pas la géolocalisation");
}
});
}

function getAPI(bdcAPI) {
return fetch(bdcAPI)
.then(response => {
    if (!response.ok) {
        throw new Error("Network response was not ok");
    }
    return response.json();
})
.then(results => {
    const locality = results.locality;
    const city = results.city;
    const countryName = results.countryName;
    const latitude = results.latitude;
    const longitude = results.longitude;

    return {
        locality: locality,
        city: city,
        countryName: countryName,
        latitude: latitude,
        longitude: longitude
    };
})
.catch(error => {
    console.error("Error during fetch:", error);
    alert("An error occurred during the fetch.");
    throw error; // Re-throw the error for the calling function to handle
});
}

async function getPosAndDisplayResult() {
try {
const { position, bdcAPI } = await getPos();
const { locality, city, countryName, latitude, longitude } = await getAPI(bdcAPI);

getCoor();

} catch (error) {
console.error(error);
}
}
async function getCoor() {
try {
const { position, bdcAPI } = await getPos();
const {latitude, longitude } = await getAPI(bdcAPI);

    nearestfromcurrent(latitude,longitude)


} catch (error) {
console.error(error);
}
}

function showresult(){
document.getElementById('result').innerText="depart:"+document.getElementById('location-lat').value+";"+document.getElementById('location-lon').value+"...destination:"+document.getElementById('destination-lat').value+";"+document.getElementById('destination-lon').value;
}


function nearestfromcurrent(latitude,longitude){

    const apiKey = 'pk.1e1d94098d9e8333fee0998d53e5deb4';
    

    
    // Construct the API request URL
    const apiUrl = `https://us1.locationiq.com/v1/reverse.php?key=${apiKey}&lat=${latitude}&lon=${longitude}&format=json`;
    
    // Make the API request using the Fetch API
    fetch(apiUrl)
      .then(response => response.json())
      .then(data => {

        const address = data.display_name || 'N/A';
        console.log(`Nearest Location: ${address}`);
        const coordinates = data.lat && data.lon ? `${data.lat}, ${data.lon}` : 'N/A';

        // Display the results
       
        console.log(`Coordinates: ${coordinates}`);
        const latitudeInput = document.querySelector('input[type="hidden"][id="location-lat"]');
        const longitudeInput = document.querySelector('input[type="hidden"][id="location-lon"]');

        latitudeInput.value =data.lat;
        longitudeInput.value =data.lon;

        

       const villeDepartInput = document.querySelector('input[name="ville_depart"]');
       villeDepartInput.value =address;
                
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
    
}