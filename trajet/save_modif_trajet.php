
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require'../config/BDD.php';
$bdd = getBdd();

require '../config/droits.php';   //voir inscription.php
require '../config/formulaire.php';
session_start();
if (empty($_POST)) {         //on s'assure que l'utilisateur n'a pas acceder à la page sans passer par (mes trajets)
    
	header ('Location: ../index.php');
	exit();
}
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Soumettre') {

    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['ville_depart']) && !empty($_POST['ville_depart'])) && (isset($_POST['ville_arrivee']) && !empty($_POST['ville_arrivee'])) && (isset($_POST['date']) && !empty($_POST['date'])) && (isset($_POST['heure']) && !empty($_POST['heure'])) && (isset($_POST['places']) && !empty($_POST['places'])) && (isset($_POST['prix']) && !empty($_POST['prix']))) {
        $contenu = action();
      
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }

}
function action() {
    global $bdd;
    
    /*on va regarder si les villes renseignées existent deja dans la base, si non alors on les ajoutes
    $sql = 'SELECT VILLE FROM ville_depart WHERE Ville="' . $_POST['ville_depart'] . '"';
    $reponse = $bdd->query($sql);
    if ($reponse->rowCount() == 0) {
        $sql2 = "INSERT INTO ville_depart (Ville) VALUES('" . $_POST['ville_depart'] . "')";
        $bdd->exec($sql2);
    }
    $sql = 'SELECT Ville FROM ville_arrivee WHERE Ville="' . $_POST['ville_arrivee'] . '"';
    $reponse = $bdd->query($sql);
    if ($reponse->rowCount() == 0) {
        $sql2 = "INSERT INTO ville_arrivee (Ville) VALUES('" . $_POST['ville_arrivee'] . "')";
        $reponse2 = $bdd->query($sql2);
    }*/
    // Update the information in the trajet table for the specified trajet_id
    $sql = 'UPDATE trajet 
    SET lieu_depart = :lieu_depart,
        destination = :destination,
        places_max = :places_max,
        date = :date,
        heure_dep = :heure_dep,
        prix = :prix
    WHERE id = :trajet_id AND pilote_user_id = :pilote_user_id';

    $statement = $bdd->prepare($sql);
    $statement->execute(array(
    ":lieu_depart" => $_POST["ville_depart"],
    ":destination" => $_POST["ville_arrivee"],
    ":places_max" => $_POST["places"],
    ":date" => $_POST["date"],
    ":heure_dep" => $_POST["heure"],
    ":prix" => $_POST["prix"],
    ":trajet_id" => $_POST["trajet_id"], // The ID of the trip we're modifying
    ":pilote_user_id" => $_SESSION["id"] // Assuming the user must be the original pilot of the trip to modify it
    ));

    return "<div class='alert alert-success'>Votre trajet a bien été modifié.</div>";
    }
$title = "Modification trajet";
gabarit();
?>