<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//sur cette page on va afficher les differents trajets de l'utilisateur
require'../config/BDD.php';
$bdd = getBdd();
                               //voir inscription.php
require '../config/droits.php';
require '../config/formulaire.php';

function disabled($int) {//fonction qui permet de mettre un boutton en gris (disabled)
    if ($int == 1) {
        return "disabled='disabled'";
    } else {
        return "";
    }
}

test_membre();
ob_start();

// Check if the user is a pilot
if ($_SESSION["pilote"]) {
    $rep_nb_trajet_pilote = $bdd->query("SELECT count(pilote_user_id) FROM trajet WHERE effectue!=1 AND pilote_user_id = " . $_SESSION["id"]);
    $nb_trajet_pilote = $rep_nb_trajet_pilote->fetch();
    // If the pilot has no ongoing trips as a driver, display a message
    if ($nb_trajet_pilote[0] == '0') {
        echo "<br><br><div class='alert alert-danger' style='margin-top:100px' >Vous n'êtes actuellement inscrit pour aucun trajet en tant que pilote, rendez-vous dans la section \"Ajouter un trajet\"</div>";
    } else {
        // Display the pilot's trips
        echo "
        <div class = 'mestrajets' style='width:1145px'>
        <h1 style='margin-top:100px'>Mes trajets en tant que pilote :</h1> ";

        // Display trips as a list
        echo "<ul class='responsive-table'>";
        echo "<li class='table-header'>
                <div class='col col-1'>Départ</div>
                <div class='col col-2'>Arrivée</div>
                <div class='col col-3'>Places prises</div>
                <div class='col col-4'>Date</div>
                <div class='col col-5'>Heure</div>
                <div class='col col-6'>Prix</div>
                <div class='col col-7'></div>
                <div class='col col-8'></div>
                <div class='col col-9'></div>
            </li>";

        $reponse = $bdd->query("SELECT * FROM trajet WHERE effectue = FALSE AND pilote_user_id = " . $_SESSION["id"]);
        // Loop through all trips
        while ($donnee = $reponse->fetch()) {
            echo "<li class='table-row'>
                    <div class='col col-1'>" . $donnee["lieu_depart"] . "</div>
                    <div class='col col-2'>" . $donnee["destination"] . "</div>
                    <div class='col col-3'>" . $donnee["places_prises"] . "/" . $donnee["places_max"] . "</div>
                    <div class='col col-4'>" . $donnee["date"] . "</div>
                    <div class='col col-5'>" . $donnee["heure_dep"] . "</div>
                    <div class='col col-6'>" . $donnee["prix"] . "</div>
                    <div class='col col-7'>
                        <form action='modifier_trajet.php' method='post'>
                            <button type='submit' name='modif' class='btn btn-white custom-btn' value='" . $donnee["id"] . "'>
                            <i class='fa-regular fa-pen-to-square'></i>
                            </button>
                        </form>
                    </div>
                    <div class='col col-8'>
                    <form action='modifier_trajet.php' method='post'>
                    <button type='submit' name='delete' class='btn btn-white custom-btn' value='" . $donnee["id"] . "'>
                        <i class='fa-solid fa-trash-can' style='color: red;'></i> 
                </form>
                
                    </div>
                    <div class='col col-9'>
                        <form action='valide_trajet.php' method='post'>
                            <button type='submit' name='valide' class='btn btn-white custom-btn' value='" . $donnee["id"] . "'>
                            <i class='fa-solid fa-check' style = 'color: green;'></i>
                            </button>
                        </form>
                    </div>
                </li>";
        }
        echo "</ul></div>";
    }
}


$rep_nb_trajet = $bdd->query("SELECT count(user_id) FROM trajet_passager,trajet WHERE effectue != 1 AND id = trajet_id AND user_id = " . $_SESSION["id"]);
$nb_trajet = $rep_nb_trajet->fetch();

if ($nb_trajet[0] == '0') {
    echo "<br><br><div class='alert '>Vous n'êtes actuellement inscrit pour aucun trajet, rendez-vous dans la section \"Rechercher un trajet\"</div>";
} else {
    echo "<div class ='mestrajetspass'>
    <h1>Mes trajets en tant que passager : </h1>";
    echo "<p>";
    echo "<div class='container'>";
    echo "<ul class='responsive-table'>";
    echo "<li class='table-header'>";
    echo "<div class='col pcol-1'>Départ</div>";
    echo "<div class='col pcol-2'>Arrivée</div>";
    echo "<div class='col pcol-3'>Disponibilite</div>";
    echo "<div class='col pcol-4'>Date</div>";
    echo "<div class='col pcol-5'>Heure</div>";
    echo "<div class='col pcol-6'>Prix</div>";
    echo "<div class='col pcol-7'>Pilote</div>";
    echo "<div class='col pcol-8'></div>";
    echo "<div class='col pcol-9'></div>";
    echo "</li>";

    $reponse = $bdd->query("SELECT * FROM user as U, trajet_passager as TP, trajet as T WHERE TP.trajet_id = T.id and TP.user_id = ".$_SESSION["id"]." AND T.pilote_user_id = U.id");

    while ($donnee = $reponse->fetch()) {
        $reponse2 = $bdd->query("SELECT * FROM evaluation WHERE trajet_id = " . $donnee["id"] . " AND evaluateur_user_id = " . $_SESSION["id"]);
        $nombre = $reponse2->fetchColumn();

        if ($nombre == 0) {
            echo "<li class='table-row'>";
            echo "<div class='col pcol-1' data-label='Départ'>" . $donnee["lieu_depart"] . "</div>";
            echo "<div class='col pcol-2' data-label='Arrivée'>" . $donnee["destination"] . "</div>";
            echo "<div class='col pcol-3' data-label='Places prises '>" . $donnee["nb_places"] . "/" . $donnee["places_max"] . "</div>";
            echo "<div class='col pcol-4' data-label='Date'>" . $donnee["date"] . "</div>";
            echo "<div class='col pcol-5' data-label='Heure'>" . $donnee["heure_dep"] . "</div>";
            echo "<div class='col pcol-6' data-label='Prix'>" . $donnee["prix"] . "</div>";
            echo "<div class='col pcol-7' data-label='Pilote'>" . $donnee["prenom"] . " " . $donnee["nom"] . "</div>";
            echo '<div class="col pcol-8"><form action="../membre/envoyer_message.php" method="post">';
            echo "<button type='submit' name='destinataire' class='btn  custom-btn-pass' value='" . $donnee["login"] . "'>
            <i class='fa-solid fa-message' style = 'color : black;'></i>                    
            </button></form>
            <div class='col pcol-9'>
            <form action='../membre/fiche_eval.php' method='post'>
            <button type='submit' " . disabled(!$donnee['effectue']) . " name='evaluer' class='btn  custom-btn-pass' value='" . $donnee["id"] . "'>
            <i class='fa-solid fa-comment-medical' style ='color : black;'></i>
            </button></form></div>
            </li>";
        }
    }

    echo "</ul>";
    echo "</div>";
    echo "</p></div>";
}

$contenu = ob_get_clean();


$title = "Mes trajets";
gabarit();

