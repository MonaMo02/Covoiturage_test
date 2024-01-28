//depart automatique
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
        const { locality, city, countryName} = await getAPI(bdcAPI);

        // Display information or perform further actions here
        const formattedValue = `${locality}, ${city}, ${countryName}`;

        const villeDepartInput = document.querySelector('input[name="ville_depart"]');
        villeDepartInput.value = formattedValue;

    } catch (error) {
        console.error(error);
    }
}
async function getCoor() {
    try {
        const { position, bdcAPI } = await getPos();
        const {latitude,longitude } = await getAPI(bdcAPI);

        const latitudeInput = document.querySelector('input[type="hidden"][name="latitude"]');
        const longitudeInput = document.querySelector('input[type="hidden"][name="longitude"]');

        if (latitudeInput && longitudeInput) {
            latitudeInput.value = latitude;
            longitudeInput.value = longitude;
        } else {
            console.error('Latitude or longitude input element not found.');
        }

    } catch (error) {
        console.error(error);
    }
}
//arrivée search 
/*function geocodeOnChange() {
    // Replace 'YOUR_API_KEY' with your actual Location IQ API key
    var apiKey = 'pk.b3131f0ea825db713292fcdae7328f5d';

    // Retrieve user input from the input field
    var userInput = document.querySelector('input[name="ville_arrivee"]').value;

    // Retrieve hidden input fields
    var latInput = document.querySelector('input[name="lat"]');
    var longInput = document.querySelector('input[name="long"]');

    // Perform geocoding with user input
    fetch(`https://us1.locationiq.com/v1/search.php?key=${apiKey}&q=${userInput}&format=json`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var firstResult = data[0];
                var latitude = firstResult["lat"];
                var longitude = firstResult["lon"];
                var address = firstResult["display_name"];

                // Output the address in 'ville_arrivee' field
                document.querySelector('input[name="ville_arrivee"]').value = address;

                // Output latitude in the 'lat' hidden input field
                if (latInput) {
                    latInput.value = latitude;
                } else {
                    console.error('Latitude input element not found.');
                }

                // Output longitude in the 'long' hidden input field
                if (longInput) {
                    longInput.value = longitude;
                } else {
                    console.error('Longitude input element not found.');
                }

                console.log("Latitude: " + latitude);
                console.log("Longitude: " + longitude);
                console.log("Address: " + address);
            } else {
                console.log("No results found.");
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}*/
function geocodeOnChange() {
    // Replace 'YOUR_API_KEY' with your actual Location IQ API key
    var apiKey = 'pk.6a78765190aaa9b1b86455b49310eaff';

    // Retrieve user input from the input field
    var userInput = document.querySelector('input[name="ville_arrivee"]').value;

    // Perform geocoding with user input
    fetch(`https://us1.locationiq.com/v1/search.php?key=${apiKey}&q=${userInput}&format=json`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var firstResult = data[0];
                var latitude = firstResult["lat"];
                var longitude = firstResult["lon"];
                var address = firstResult["display_name"];

                // Output the address in 'ville_arrivee' field
                document.querySelector('input[name="ville_arrivee"]').value = address;

                // Call the getCoordinates function to update hidden input values
                getCoordinates();

                console.log("Latitude: " + latitude);
                console.log("Longitude: " + longitude);
                console.log("Address: " + address);
            } else {
                console.log("No results found.");
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}

function getCoordinates() {
    // Retrieve hidden input fields for latitude and longitude
    var latInput = document.querySelector('input[name="lat"]');
    var longInput = document.querySelector('input[name="long"]');

    // Fetch the latest latitude and longitude from the geocoding results
    var latitude = parseFloat(document.querySelector('input[name="latitude"]').value);
    var longitude = parseFloat(document.querySelector('input[name="longitude"]').value);

    // Update hidden input values
    if (latInput) {
        latInput.value = latitude;
    } else {
        console.error('Latitude input element not found.');
    }

    if (longInput) {
        longInput.value = longitude;
    } else {
        console.error('Longitude input element not found.');
    }
}

