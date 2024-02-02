<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require'../config/BDD.php';
$bdd = getBdd();
require'../config/formulaire.php';      //voir inscription.php
require '../config/droits.php';

test_pilote();
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Soumettre') {

    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['ville_depart']) && !empty($_POST['ville_depart'])) && (isset($_POST['ville_arrivee']) && !empty($_POST['ville_arrivee'])) && (isset($_POST['date']) && !empty($_POST['date'])) && (isset($_POST['heure']) && !empty($_POST['heure'])) && (isset($_POST['places']) && !empty($_POST['places'])) && (isset($_POST['prix']) && !empty($_POST['prix']))) {
        $contenu = action();
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();
}

function formulaire()
{
    ob_start();
?>
    <h1 id="ajttrajetheader" style="margin-top:100px;" >Publier un trajet</h1>
    <div class="publier_trajet">

        <?php
        form_debut("form", "POST", "ajout_trajet.php");?>

        <input type="text" name="ville_depart" id="location" placeholder="D’où partez-vous ?" >
            
        <button title="click to get exact position" onclick="getPosAndDisplayResult()"><i class="fa-solid fa-crosshairs"></i></button>
        <div id="autocomplete-suggestions-location" ></div>

        <input type="text"  id="destination"  placeholder="Où allez-vous?"><br>
        <input type="hidden" id="location-lat" name="location-lat">
        <input type="hidden" id="location-lon" name="location-lon">
        <input type="hidden" id="destination-lat" name="destination-lat">
        <input type="hidden" id="destination-lon" name="destination-lon">
        <div id="autocomplete-suggestions-destination"></div>

        <div id="result"></div>
        <script src="../templates/js/geolocation.js"></script>
        <span style="color: red;" id="verifdate"></span>
        <input type="time" id="heure" required class="datetimeTRJ">
        <input type="date" name="date" id="date" required class="datetimeTRJ"><br>
        <script>
            document.getElementById('date').setAttribute('min', new Date().toISOString().split('T')[0]);
            document.getElementById('date').setAttribute('value', new Date().toISOString().split('T')[0]);
        </script>
        <br>
        <input type="number" name="places" id="places" placeholder="2 places" min="0" max="5">
        <input type="text" name="prix" id="prix" placeholder="100 da">
        <?php
       
        echo "<br><br>";
        form_submit("Soumettre", "Soumettre", FALSE);
        
        echo "<br>";
        form_fin();
        ?>
    </div>
<?php
    return ob_get_clean();
}

function action()
{
    global $bdd;

    //on va regarder si les villes renseignées existent deja dans la base, si non alors on les ajoutes
    $sql = 'SELECT nom FROM ville_depart WHERE nom="' . $_POST['ville_depart'] . '";';
    $reponse = $bdd->query($sql);
    if ($reponse->rowCount() == 0) {
        $sql2 = "INSERT INTO ville_depart (nom,locationlat,locationlon) VALUES(:nom, :latitude, :longitude);";
        $statement = $bdd->prepare($sql2);
        $statement->execute(array(
            ":nom" => $_POST['ville_depart'],
            ":latitude" => $_POST['location-lat'],
            ":longitude" => $_POST['location-lon']
        ));
    }
    $sql = 'SELECT nom FROM ville_arrivee WHERE nom="' . $_POST['ville_arrivee'] . '"';
    $reponse = $bdd->query($sql);
    if ($reponse->rowCount() == 0) {
        $sql2 = "INSERT INTO ville_arrivee (nom,destinationlat,destinationlon) VALUES(:nom, :latitude, :longitude);";
        $statement = $bdd->prepare($sql2);
        $statement->execute(array(
            ":nom" => $_POST['ville_arrivee'],
            ":latitude" => $_POST['destination-lat'],
            ":longitude" => $_POST['destination-lon']
        ));
    }

    //on insert les infos dans la table trajet
    $sql = 'INSERT INTO trajet (lieu_depart,destination,places_max,places_prises,date,pilote_user_id,heure_dep,prix,effectue) VALUES(:lieu_depart,:destination,:places_max,:places_prises,:date,:pilote_user_id,:heure_dep,:prix,:effectue);';
    $statement = $bdd->prepare($sql);
    $result =$statement->execute(array(
        ":lieu_depart" => $_POST["ville_depart"],
        ":destination" => $_POST["ville_arrivee"],
        ":places_max" => $_POST["places"],
        ":places_prises" => "0", //on initalise le nombre de places prises à 0
        ":date" => $_POST["date"],
        ":pilote_user_id" => $_SESSION["id"],
        ":heure_dep" => $_POST["heure"],
        ":prix" => $_POST["prix"],
        ":effectue" => FALSE,
    ));

    if($result){
        return "<div class='alert alert-success'>Votre trajet a bien été enregistré.</div>";
    }
    else{
        return "<div class='alert alert-success'>RIDE NOT ADDED </div>";

    }

}

$title = "Inscription trajet";
gabarit();
?>