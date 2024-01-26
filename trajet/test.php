<?php
$servername = "localhost"; // Replace with your database server name
$username = "root";       // Replace with your database username
$password = "your_password";           // Replace with your database password
$database = "covoiturage";       // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM ville_depart";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the data
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Convert the data to a JSON string
    $json_data = json_encode($data);

    // Close the result set
    $result->close();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpooling Find Trajet</title>
</head>

<body>
    <h1>Carpooling Find Trajet</h1>

    <!-- Find Trajet form -->
    <input type="hidden" name="db" id="db" value='<?php echo $json_data; ?>'>

    <pre><?php print_r($data); ?></pre>
    <form onsubmit="findTrajets(); return false;">
    <label for="location">Enter departure:</label>
    <input type="text" id="location" required>
    <label for="time">time</label>
    <input type="time" id="time" required>
    <label for="date">date</label>
    <input type="date" id="date" required>
    <label for="destination">destination</label>
    <input type="text" id="destination" required>
    <button type="submit">Find Trajets</button>
</form>
<div id="autocomplete-suggestions-location"></div>
<div id="autocomplete-suggestions-destination"></div>

    <div id="results-container"></div>

<script>
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
        // Retrieve user inputs
        const departureInput = document.getElementById('location').value;
        const destinationInput = document.getElementById('destination').value;
        const timeInput = document.getElementById('time').value;
        const dateInput = document.getElementById('date').value;

        // Parse the JSON data
console.log('JSON Data:', jsonData);
var parsedData = JSON.parse(jsonData);

        // Use the first result's coordinates from the parsedData
        if (parsedData.length > 0) {
            const firstResult = parsedData[0];
            const latitude = firstResult.lat;
            const longitude = firstResult.lon;

            console.log(`Coordinates for ${departureInput}: Latitude ${latitude}, Longitude ${longitude}`);

            // Call the function to find nearby places
            findNearbyPlaces(latitude, longitude, parsedData, destinationInput, timeInput, dateInput);
        } else {
            console.error(`No coordinates found in the parsedData for ${departureInput}`);
        }

        // Clear suggestion lists
        document.getElementById('autocomplete-suggestions-location').innerHTML = '';
        document.getElementById('autocomplete-suggestions-destination').innerHTML = '';
    } catch (error) {
        console.error('Error in findTrajets:', error);
    }
}


    function fetchAutocompleteSuggestions(query, inputId) {
        const apiKey = 'pk.6a78765190aaa9b1b86455b49310eaff';

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
                getGeocodingDetails(inputId, suggestion.lat, suggestion.lon);
                autocompleteSuggestions.innerHTML = '';
            });
            autocompleteSuggestions.appendChild(suggestionItem);
        });
    }

    function findNearbyPlaces(latitude, longitude, trajets, destinationInput, timeInput, dateInput) {
        // Call Nearby Places API to find nearby places around the inputted departure location
        fetch(`https://us1.locationiq.com/v1/nearby.php?key=pk.6a78765190aaa9b1b86455b49310eaff&lat=${latitude}&lon=${longitude}&radius=1000`)
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

        const filteredTrajets = trajets.filter(trajet => {
            // Check if the trajet's departure is in the list of nearby places within the specified radius
            const isNearby = nearbyPlaces.some(place =>
                Math.abs(parseFloat(place.lat) - parseFloat(trajet.lat)) < radius &&
                Math.abs(parseFloat(place.lon) - parseFloat(trajet.lon)) < radius
            );

            // Check additional conditions for destination, time, and date
            const isMatchingDestination = destinationInput === trajet.destination;
            const isMatchingTime = timeInput === trajet.time;
            const isMatchingDate = dateInput === trajet.date;

            return isNearby && isMatchingDestination && isMatchingTime && isMatchingDate;
        });

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
                const destinationInfo = `Departure: (${trajet.lat}, ${trajet.lon}), Destination: ${trajet.destination.name}, Date: ${trajet.date}, Time: ${trajet.time}`;
                
                // Create a clickable link
                const link = document.createElement('a');
                link.href = `reserve.php?${encodeURI(destinationInfo)}`;
                link.textContent = destinationInfo;
                
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
    
</script>
</body>

</html>
