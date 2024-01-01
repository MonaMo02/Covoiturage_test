<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require'../config/BDD.php';
$bdd = getBdd();

require '../config/droits.php';
require '../config/formulaire.php';

test_membre();
ob_start();$bdd = getBdd();
$resultat = $bdd->query("SELECT * from messagerie as M, user as U WHERE M.destinataire_user_id = " . $_SESSION["id"] . " AND U.id=M.expediteur_user_id  ORDER BY M.id DESC");
$index = 0;
$donnee = $resultat->fetch();
if($donnee!=FALSE){
    echo "<h1>Mes messages:</h1>"; //on affiche les message de l'utilisateur, du plus recent au plus ancien, grace a la requete SQL de la ligne 19
    
    while ($donnee) { //on parcours la requete pour afficher chaque message
        $index = $index + 1;
        $tab_date = explode(" ", $donnee["date"]);
        $tab_jour = explode("-", $tab_date[0]);
        echo '<form action="envoyer_message.php" method="post">'; //chaine de echo pour ameliorer l'ergonomie de la messagerie avec les fonctions du framework bootstrap
        echo "<div class=\"panel panel-success\">";
        echo "<div class=\"panel-heading clearfix\">";
        echo "<b>Le " . $tab_jour[2] . " " . $tab_jour[1] . " " . $tab_jour[0] . " à " . $tab_date[1] . "</b>";

        echo "<button class=\"btn btn-primary pull-right btn-margin-right\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapseExample" . $index . "\" aria-expanded=\"false\" aria-controls=\"collapseExample\">";
        echo "Lire le message";
        echo "</button>";


        echo "<button type='submit' name='destinataire' class='btn btn-success pull-right btn-margin-right' value='" . $donnee["username"] . "'>Répondre</button></form>";

        echo "</div>";

        echo "<div class=\"collapse\" id=\"collapseExample" . $index . "\">";
        echo "<div class=\"well\">";
        echo "<div class=\"panel-body\">";
        try {

            echo "<h2><u>Expediteur :</u> " . $donnee["prenom"] . " " . $donnee["nom"] . "</h2>";
            echo "<h2><U>Titre :</U> ";
            echo "" . $donnee["titre"] . "</h2>";
            echo "<h2><U>Message : </u></h2>";
            echo "<p>" . $donnee["message"] . "</p>";
            echo "<br>";
        } catch (PDOException $e) {
            echo 'Échec : ' . $e->getMessage();
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
}}
else{echo "<br><br><div class='alert alert-danger'>Vous n'avez aucun message recçu.</div>";}
$contenu = ob_get_clean();


$title = "Mes messages";
gabarit();

