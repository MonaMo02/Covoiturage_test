<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require '../config/BDD.php';
$bdd = getBdd();
//voir inscription.php
require '../config/droits.php';
require '../config/formulaire.php';

test_membre();

$contenu = formulaire();


function formulaire() {
    
    global $bdd;
        
    $result = $bdd->query("SELECT 
    * from trajet
    JOIN ville_depart ON trajet.lieu_depart = ville_depart.nom
    JOIN ville_arrivee ON trajet.destination = ville_arrivee.nom
    WHERE trajet.effectue = 0 and trajet.places_max > trajet.places_prises
    AND pilote_user_id !=" . $_SESSION["id"] );

// Check if the query was successful
    if ($result) {
        // Fetch the data
        $data = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Convert the data to a JSON string
    $json_data = json_encode($data);
    
    // Close the database connection
    $result->closeCursor(); // Close the cursor, releasing the lock on the table
    $bdd = null; // Close the connection
} else {
    echo "Error: " .$result. "<br>" . $bdd->errorInfo()[2];

    
}
if ($data != FALSE) {
        ob_start(); ?>
        <h1 style="margin-top:100px;" >Recherche de votre trajet</h1>
        <input type="hidden" name="db" id="db" value='<?php echo $json_data; ?>'>
        <input type="hidden" id="location-lat">
        <input type="hidden" id="location-lon">
        <input type="hidden" id="destination-lat">
        <input type="hidden" id="destination-lon">

        <!-- <pre><?php print_r($data); ?></pre> -->
        <form onsubmit="findTrajets(); return false;">
            <label for="location">Enter departure:</label>
            <input type="text" id="location" required autocomplete="off">
            <div id="autocomplete-suggestions-location"></div>

            <label for="time">Time:</label>
            <input type="time" id="time" required>

            <label for="date">Date:</label>
            <input type="date" id="date" min="yyyy-mm-dd" required>

            <label for="destination">Enter destination:</label>
            <input type="text" id="destination" required autocomplete="off">
            <div id="autocomplete-suggestions-location"></div>
            <div id="autocomplete-suggestions-destination"></div>

            <button type="submit">Find Trajets</button>
        </form>

        <div id="results-container"></div>
        <script src = "../templates/js/findtrajet.js"></script>
        <script>
                  document.getElementById('date').setAttribute('min', new Date().toISOString().split('T')[0]);
                  document.getElementById('date').setAttribute('value', new Date().toISOString().split('T')[0]);
        </script>
        
    <?php
        return ob_get_clean();
    } else {
        return "<h3 style='margin-top:100px;'>Il n'y a aucun trajet pour le moment.</h3>";
    }
}
    
    

$title = "Recherche trajet";
gabarit();
?>
