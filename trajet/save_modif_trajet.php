
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require '../config/BDD.php';
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
    if ((isset($_POST['date']) && !empty($_POST['date'])) && (isset($_POST['heure']) && !empty($_POST['heure'])) && (isset($_POST['places']) && !empty($_POST['places'])) && (isset($_POST['prix']) && !empty($_POST['prix']))) {
        $contenu = action();
      
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }

}
function action() {
    global $bdd;
    $sql = 'UPDATE trajet 
    SET places_max = :places_max,
        date = :date,
        heure_dep = :heure_dep,
        prix = :prix
    WHERE id = :trajet_id AND pilote_user_id = :pilote_user_id';
    $statement = $bdd->prepare($sql);
    $statement->execute(array(
    ":places_max" => $_POST["places"],
    ":date" => $_POST["date"],
    ":heure_dep" => $_POST["heure"],
    ":prix" => $_POST["prix"],
    ":trajet_id" => $_POST["trajet_id"], // The ID of the trip we're modifying
    ":pilote_user_id" => $_SESSION["id"] // Assuming the user must be the original pilot of the trip to modify it
    ));

    return "<div style='margin-top:100px;' class='alert alert-success'>Votre trajet a bien été modifié.</div>";
    }
$title = "Modification trajet";
gabarit();
?>