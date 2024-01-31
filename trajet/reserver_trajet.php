<?php

// $choix_trajet = $_GET["choix_trajet"];// on recupere l'id du trajet choisit à reserver

require '../config/BDD.php';
$bdd = getBdd();
                        //voir inscription.php
require '../config/droits.php';
require '../config/formulaire.php';

test_membre();

if (!isset($_GET['choix_trajet'])&&!isset($_POST['choix_trajet'])) {//on verifie que l'utilisateur vient bien de la page recherche_trajet.php
    header('Location: ../index.php');
    exit();
}else if(isset($_GET['choix_trajet'])){
    $choix_trajet = $_GET["choix_trajet"];

}else if(isset($_POST['choix_trajet'])){
    $choix_trajet = $_POST["choix_trajet"];   
}
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Reservation']) && $_POST['Reservation'] == 'Reservation') {
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['nb_places']) && !empty($_POST['nb_places']))) {
        $contenu = action();
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();//on affiche le formulaire car l'utilisateur vient d'arriver sur la page
}

function formulaire() {

    global $bdd;
    //on recupère les informations sur le trajet
    global $choix_trajet;
    $reponse = $bdd->query("SELECT * FROM trajet as T, user as U, pilote as P WHERE T.id = " . $choix_trajet." AND U.id= T.pilote_user_id AND P.pilote_user_id = T.pilote_user_id");
    $donnee = $reponse->fetch();
    ob_start();
    ?>
    <h1>Reserver votre trajet</h1>
    
    
    <?php
    if ($donnee ==true) {
    //on affiche les informations du trajet
    echo"<div class='panel panel-success'>";
    echo"<div class='panel-heading'>";
    
    echo"<b>Ville de départ : </b> ".$donnee["lieu_depart"]." - <b>Ville d'arrivée : </b>".$donnee["destination"];

    echo"</div>";
    echo "<div class='panel-body'>";

    echo" Pilote : ".$donnee["prenom"]." ".$donnee["nom"];
        
    echo"  <a href='../membre/profil.php?username=".$donnee["login"]."' class='btn btn-primary pull-right'>Son profil</a></br>";
    echo "Voiture : ".$donnee["voiture_marque"]." ".$donnee["voiture_modele"];

        echo"</div>";
    echo "</div>";
        
        
        
        
        
    form_debut("form", "POST", "reserver_trajet.php");
    form_label("Nombre de places");
    //on limite le nombre de places à celle disponible en soustrayant le nombre de places max par le nombre de places prises
    $tab_places = range(1, $donnee["places_max"] - $donnee["places_prises"]);
    form_select("nb_places", FALSE,1, $tab_places);
    form_hidden("choix_trajet",$choix_trajet);//on garde en memoire l'id du trajet par un champ hidden
    form_submit("Reservation", "Reservation", FALSE);
    form_fin();
    ?>
    <?php
    return ob_get_clean();
    }else {
        // Handle the case when no row is found in the database
        echo "No matching record found in the database.";
    }
}
function action() {
    global $bdd;
    global $choix_trajet;

    // Get the maximum available places from the trajet table
    $max_available_places = $bdd->query("SELECT places_max FROM trajet WHERE id = " . $choix_trajet)->fetchColumn();

    // Check if the sum of current places_prises and new nb_places exceeds max_available_places
    $total_places_prises = $bdd->query("SELECT places_prises FROM trajet WHERE id = " . $choix_trajet)->fetchColumn();
    $new_places = $_POST['nb_places'];




    if (($total_places_prises + $new_places) > $max_available_places) {
 
   
        return "<div class='alert alert-danger'>Le nombre de places disponibles est insuffisant.";
    } else if(($total_places_prises + $new_places) == $max_available_places){

        $bdd->exec("UPDATE trajet SET effectue = 1 WHERE id = " . $choix_trajet);
        return "<div class='alert alert-success'>Votre trajet a bien été réservé, il est maintenant disponible dans votre espace 'mes trajets'.</div>";
    }else
        // Update the number of places prises on the trajet
        $sql = 'INSERT INTO trajet_passager (trajet_id,user_id,nb_places) VALUES(:trajet_id,:user_id,:nb_places)';
        $statement = $bdd->prepare($sql);
        $statement->execute(array(
            ":trajet_id" => $choix_trajet,
            ":user_id" => $_SESSION["id"],
            ":nb_places" => $new_places
        ));

        $bdd->exec("UPDATE trajet SET places_prises = places_prises + " . $new_places . " WHERE id = " . $choix_trajet);

        return "<div class='alert alert-success'>Votre trajet a bien été réservé, il est maintenant disponible dans votre espace 'mes trajets'.</div>";
    }



$title = "Reserver trajet";
gabarit();

