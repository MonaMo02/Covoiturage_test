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

// //si l'utilisateur est un pilote alors on affiche ses trajets en tant que pilote
// if ($_SESSION["pilote"]) {
//     $rep_nb_trajet_pilote = $bdd->query("SELECT count(pilote_user_id) FROM trajet WHERE effectue!=1 AND pilote_user_id = " . $_SESSION["id"]  );
//     $nb_trajet_pilote = $rep_nb_trajet_pilote->fetch();
//     //si le pilote n'a aucun trajet en cours en tant que conducteur on l'affiche
//     if ($nb_trajet_pilote[0] == '0') {
//         echo "<br><br><div class='alert alert-danger'style='margin-top:100px' >Vous n'êtes actuellement inscrit pour aucun trajet en tant que pilote, rendez-vous dans la section \"Ajouter un trajet\"</div>";
//     } else { //sinon on affiche les trajets
//         echo "<h1 style='margin-top:100px'>Mes trajets en tant que pilote :</h1> ";
       
//         //on fait un tableau contenant les infos des trajets
//         echo "<p><table class='table table-hover'><tr><th>Départ</th><th>Arrivée</th><th>Places prises</th><th>Date</th><th>Heure</th><th>Prix</th><th></th><th></th><th></th></tr>";
//         $reponse = $bdd->query("SELECT * FROM trajet WHERE effectue = FALSE AND pilote_user_id = " . $_SESSION["id"]);//on affiche que les trajets non effectue
//        //on parcourt tous les trajets
//         while ($donnee = $reponse->fetch()) {
//             echo"<tr><td>" . $donnee["lieu_depart"] . "</td><td>" . $donnee["destination"] . "</td><td>" . $donnee["places_prises"] . "/" . $donnee["places_max"] . "</td><td>" . $donnee["date"] . "</td><td>" . $donnee["heure_dep"] . "</td><td>" . $donnee["prix"] . "</td>";
            
//             //on va faire un formulaire pour chaque boutton submit (supprimer, valider, liste) afin d'envoyer par la methode POST l'id du trajet aux differentes pages
//             echo '<td><form action="modifier_trajet.php" method="post">';
//             echo "<button type='submit' name='modif' class='btn btn-safe' value='" . $donnee["id"] . "'>Modifier</button></form></td>";
//             echo '<td><form action="delete_trajet.php" method="post">';
//             echo "<button type='submit' name='suppr' class='btn btn-danger' value='" . $donnee["id"] . "'>Supprimer</button></form></td>";
//             echo '<td><form action="valide_trajet.php" method="post">';
//             echo "<button type='submit' name='valide' class='btn btn-success' value='" . $donnee["id"] . "'>Valider</button></form></td>";
//             echo '<td><form action="liste_user_trajet.php" method="post">';
//             echo "<button type='submit' name='liste' class='btn btn-info' value='" . $donnee["id"] . "'>Liste Passagers</button></form></td>";
//             echo"</tr>";
//         }
//         echo"</table></p>";
//     }
// }



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
        <div class = 'mestrajets'>
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
    echo "<div class ='mestrajets'><h1>Mes trajets en tant que passager : </h1>";
    echo "<p><table class='table table-hover'><tr><th>Départ</th><th>Arrivée</th><th>Places prises</th><th>Date</th><th>Heure</th><th>Prix</th><th>Pilote</th><th></th><th></th></tr>";

    $reponse = $bdd->query("SELECT * FROM user as U, trajet_passager as TP, trajet as T WHERE TP.trajet_id = T.id and TP.user_id = ".$_SESSION["id"]." AND T.pilote_user_id = U.id");

    while ($donnee = $reponse->fetch()) {
        $reponse2 = $bdd->query("SELECT * FROM evaluation WHERE trajet_id = " . $donnee["id"] . " AND evaluateur_user_id = " . $_SESSION["id"]);
        $nombre = $reponse2->fetchColumn();

        if ($nombre == 0) {
            echo "<tr><td>" . $donnee["lieu_depart"] . "</td><td>" . $donnee["destination"] . "</td><td>" . $donnee["nb_places"] . "/" . $donnee["places_max"] . "</td><td>" . $donnee["date"] . "</td><td>" . $donnee["heure_dep"] . "</td><td>" . $donnee["prix"] . "</td><td>" . $donnee["prenom"] . " " . $donnee["nom"] . "</td>";

            echo '<td><form action="../membre/envoyer_message.php" method="post">';
            echo "<button type='submit' name='destinataire' class='btn btn-info custom-btn' value='" . $donnee["login"] . "'>Envoyer message au conducteur</button></form></td>";

            echo '<td><form action="../membre/fiche_eval.php" method="post">';
            echo "<button type='submit' " . disabled(!$donnee['effectue']) . " name='evaluer' class='btn btn-success custom-btn' value='" . $donnee["id"] . "'>Evaluer</button></form></td>";
        }
    }
    echo "</table></p></div>";
}

$contenu = ob_get_clean();


$title = "Mes trajets";
gabarit();

