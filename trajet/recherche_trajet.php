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
    AND pilote_user_id !=" . $_SESSION["id"]);

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
        <h1 style="margin-top:100px; font-weight: 600;font-size: 50;color: #14525c;text-align: center;margin-bottom: 40px;" >Recherche de votre trajet</h1>
        <div class="rechercheSub">

        <input type="hidden" name="db" id="db" value='<?php echo $json_data; ?>'>
        <input type="hidden" id="location-lat">
        <input type="hidden" id="location-lon">
        <input type="hidden" id="destination-lat">
        <input type="hidden" id="destination-lon">

        <!-- <pre><?php print_r($data); ?></pre> -->
        <div class="quick-search-form"  style="margin-top: 20px;"> 
            <form id="searchForm"  method="POST" onsubmit="findTrajets(); return false;">
                <input type="text" name="ville_depart" class='start input' placeholder='Depart' required  id="location"  autocomplete="off"/>                   
                <input type="text" name="ville_arrivee" class='dest input' placeholder='Destination' required id="destination" autocomplete="off"/>
                <input type="date"  id="date" class="date input" name="date" min="yyyy-mm-dd" placeholder="Select a date">
                <input type="time" id="time" required>
                <input type="number" name="nbseat" class='nbseat input' placeholder='0' min='0' max='5'/>
                <input type="submit" value="Search" class="submit-search">
            </form>

            <div id="autocomplete-suggestions-location"></div>
            <div id="autocomplete-suggestions-destination"></div>
            
            
            <script>
                document.getElementById('date').setAttribute('min', new Date().toISOString().split('T')[0]);
                document.getElementById('date').setAttribute('value', new Date().toISOString().split('T')[0]);

                document.addEventListener('DOMContentLoaded', function () {
                    var locationInput = document.getElementById('location');
                    var destinationInput = document.getElementById('destination');
                    var suggestionsLocation = document.getElementById('autocomplete-suggestions-location');
                    var suggestionsDestination = document.getElementById('autocomplete-suggestions-destination');

                    locationInput.addEventListener('click', function () {
                        suggestionsDestination.style.display = 'none';
                        suggestionsLocation.style.display = 'block';
                    });

                    destinationInput.addEventListener('click', function () {
                        suggestionsLocation.style.display = 'none';
                        suggestionsDestination.style.display = 'block';
                    });
                });
            </script>
            
            
       

        <div id="results-container" style="margin-top:50px;  margin-left : 250px ;max-width: 800px;">
    
        </div>
        <script src = "../templates/js/findtrajet.js"></script>
        </div>
    <?php
        return ob_get_clean();
    } else {
        return "<h3 style='margin-top:100px;'>Il n'y a aucun trajet pour le moment.</h3>";
    }
}
    
    

$title = "Recherche trajet";
gabarit();
?>
