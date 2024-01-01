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


    $contenu = formulaire();

$trajet_id=$_POST["modif"];//on recupere l'id du trajet à supprimer
// Fetch existing values for this trajet_id from the database
$sql2 = "SELECT * FROM trajet WHERE id = :trajet_id";
$statement = $bdd->prepare($sql2);
$statement->execute(array(":trajet_id" => $trajet_id));
$existingValues = $statement->fetch(PDO::FETCH_ASSOC);


ob_start();

// Fetching passenger details to inform about the trajet chagement
$reponse = $bdd->query("SELECT user_id, prenom, nom FROM trajet_passager, user WHERE trajet_id = " .$trajet_id." AND id=user_id");
while($donnee = $reponse->fetch()){
   echo "Le trajet a été modifié. Veuillez vérifier les détails mis à jour.<br><br>";
   
   // Send a notification to passengers about the trip modification
   $sql = 'INSERT INTO messagerie (expediteur_user_id, destinataire_user_id, titre, message, date)
           VALUES(:expediteur_user_id, :destinataire_user_id, :titre, :message, :date)';
   
   $statement = $bdd->prepare($sql);
   $statement->execute(array(
       ":destinataire_user_id" => $donnee["user_id"],
       ":expediteur_user_id" => $_SESSION["id"],
       ":message" => "Cher ".$donnee["prenom"]." ".$donnee["nom"].", le trajet a été modifié. Veuillez vérifier les détails mis à jour.",
       ":titre" => "Modification du trajet",
       ":date" => date('Y-m-d H:i:s')
   ));
}
function formulaire() {
    ob_start();
    ?>
    <h1>Modification de votre trajet</h1>
    <?php
    $bdd = getBdd();
    $trajet_id=$_POST["modif"];//on recupere l'id du trajet à supprimer
    
    // Fetch existing values for this trajet_id from the database
    $sql2 = "SELECT * FROM trajet WHERE id = :trajet_id";
    $statement = $bdd->prepare($sql2);
    $statement->execute(array(":trajet_id" => $trajet_id));
    $existingValues = $statement->fetch(PDO::FETCH_ASSOC);

    form_debut("form", "POST", "save_modif_trajet.php");
    form_label("Ville de départ");
    form_input_text("ville_depart", TRUE, "", $existingValues["lieu_depart"], 30, "");
    echo "<br><br>";
    form_label("Ville d'arrivée");
    form_input_text("ville_arrivee", TRUE, "", $existingValues["destination"], 30, "");  //on construit le formulaire pour recuperer le trajet
    echo "<br><br>";
    form_label("Date du trajet (yyyy-mm-dd)");
    form_input_text("date", "TRUE", "", $existingValues["date"], 30, "verifdate();");
    echo "<span style=\"color: red;\" id=\"verifdate\"></span>";
    echo "<br><br>";
    form_label("Heure");
    form_input_text("heure", TRUE, "", $existingValues["heure_dep"], 20, "");
    echo "<br><br>";
    form_label("Nombre de place disponibles");
    form_input_text("places", TRUE, "", ($existingValues["places_max"]-$existingValues["places_prises"]), 5, "");
    echo "<br><br>";
    form_label("Prix");
    form_input_text("prix", TRUE, "..DA..", $existingValues["prix"], 15, "");
    echo "<br><br>";
    echo("<input type='hidden' name='trajet_id' id='trajet_id' value=' $trajet_id'>");
    echo("<input type='hidden' name='trajet_id' id='trajet_id' value=' $trajet_id'>");
    form_submit("Soumettre", "Soumettre", FALSE);
    form_reset("reset", "", FALSE, FALSE);
    echo "<br>";
    form_fin();
    ?>
    <?php
    return ob_get_clean();
}

$title = "Modification trajet";
gabarit();
?>