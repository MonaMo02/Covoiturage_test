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
    <h1 style="margin-top:100px;" >Inscription de votre trajet</h1>
    <?php
    form_debut("form", "POST", "ajout_trajet.php");
    form_label("ville de départ");
    form_input_position("ville_depart", TRUE, "", "", 30, "", "", "result");
    echo'<input type="hidden" name="latitude" value="">';
    echo '<input type="hidden" name="longitude" value="">';
    echo '<button type="button" onclick="getCoor()">Envoyer mes coordonnées</button>';
    echo '<div id="result"> Il faut cliquer sur le bouton "Envoyer mes coordonnées"</div>';
    echo "<br><br>";
    form_label("ville d'arrivée");
    form_input_text("ville_arrivee", "TRUE", "", "", 60, "geocodeOnChange();");
    echo '<button type="button" onclick="getCoordinates()">Confirmer ma destination</button>';
    echo'<input type="hidden" name="lat" value="">';
    echo '<input type="hidden" name="long" value="">';
    echo '<script src="../templates/js/geolocation.js"></script>';
    echo "<br><br>";   
    form_label("Date du trajet (yyyy-mm-dd)");
    form_input_text("date", "TRUE", "", "", 30, "verifdate();");
    echo "<span style=\"color: red;\" id=\"verifdate\"></span>";
    echo "<br><br>";
    form_label("Heure");
    form_input_text("heure", TRUE, "", "", 20, "");
    echo "<br><br>";
    form_label("Nombre de place disponibles");
    form_input_text("places", TRUE, "", "", 5, "");
    echo "<br><br>";
    form_label("Prix");
    form_input_text("prix", TRUE, "DINARS ALGERIENS", "", 15, "");
    echo "<br><br>";
    form_submit("Soumettre", "Soumettre", FALSE);
    form_reset("reset", "", FALSE, FALSE);
    echo "<br>";
    form_fin();
    ?>
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
        $sql2 = "INSERT INTO ville_depart (nom, latitude, longitude) VALUES(:nom, :latitude, :longitude);";
        $statement = $bdd->prepare($sql2);
        $statement->execute(array(
            ":nom" => $_POST['ville_depart'],
            ":latitude" => $_POST['latitude'],
            ":longitude" => $_POST['longitude']
        ));
    }
    $sql = 'SELECT nom FROM ville_arrivee WHERE nom="' . $_POST['ville_arrivee'] . '"';
    $reponse = $bdd->query($sql);
    if ($reponse->rowCount() == 0) {
        $sql2 = "INSERT INTO ville_arrivee (nom, latitude, longitude) VALUES(:nom, :latitude, :longitude);";
        $statement = $bdd->prepare($sql2);
        $statement->execute(array(
            ":nom" => $_POST['ville_arrivee'],
            ":latitude" => $_POST['latitude'],
            ":longitude" => $_POST['longitude']
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