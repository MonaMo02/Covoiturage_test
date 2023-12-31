<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require'../config/BDD.php';
$bdd = getBdd();
//voir inscription.php
require '../config/droits.php';
require '../config/formulaire.php';

test_membre();
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Soumettre') {
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if (isset($_POST['ville_depart']) && isset($_POST['ville_arrivee']) && isset($_POST['date'])) {
        $contenu = action();
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();
}

function formulaire() {

    global $bdd;
    ?>

    <?php
    $reponse = $bdd->query("SELECT * FROM trajet WHERE effectue = 0 and pilote_user_id != " . $_SESSION["id"]);
    $donnee = $reponse->fetch(); //on verifie qu'il y a bien des trajets dans la base afin d'eviter des erreurs
    if ($donnee != FALSE) {
        ob_start();
        echo"<h1>Recherche de votre trajet</h1>";
        form_debut("form", "POST", "recherche_trajet.php");
        form_label("Ville de départ");
        form_input_text("ville_depart", TRUE, "", "", 30, " ");
        echo"<br>";
        
        //on construit des listes déroulantes avec les villes contenues dans la base et les dates
        // form_select_sql_attribut_ville("Ville de départ", "ville_depart", 1, $bdd, "lieu_depart", "trajet");
        // echo"<br><br>";
        form_label("Ville d'arrivée");
        form_input_text("ville_arrivee", TRUE, "", "", 30, " ");

        echo"<br>";
        // form_select_sql_attribut_ville("Ville d'arrivée", "ville_arrivee", 1, $bdd, "lieu_arrivee", "trajet");
        echo"<br><br>";
        form_label("Date");
        echo"<br>";
        form_select_sql_attribut_ville("Date", "date", 1, $bdd, "date", "trajet");
        echo"<br><br>";

        form_submit("Soumettre", "Soumettre", FALSE);
        form_reset("Reset", "Reinitialiser", FALSE, FALSE);
        form_fin();
        return ob_get_clean();
    } else {
        return "Il n'y a aucun trajet pour le moment.";
    }
    ?>
    <?php
}

function action() {

    global $bdd;
    //on va chercher dans la base des données les trajets correspondant au valeurs choisies par l'utilisateur
    //$bdd->query("SELECT * FROM trajet WHERE lieu_depart = $_POST['ville_depart'] AND lieu_arrivee = $_POST['ville_arrivee'] AND date = $_POST['date'] AND effectue = FALSE AND pilote_user_id != $_SESSION["id"]");
    // $reponse->execute(array($_POST['ville_depart'],
    //     $_POST['ville_arrivee'],
    //     $_POST['date'],
    //     FALSE,
    //     $_SESSION["id"]
    // ));

    // Assuming $bdd is your database connection

// Prepare the statement
$stmt = $bdd->prepare("SELECT * FROM trajet WHERE lieu_depart = :ville_depart AND destination = :destination AND date = :date AND effectue = FALSE AND pilote_user_id != :user_id");

// Bind parameters
$stmt->bindParam(':ville_depart', $_POST['ville_depart']);
$stmt->bindParam(':destination', $_POST['ville_arrivee']);
$stmt->bindParam(':date', $_POST['date']);
$stmt->bindParam(':user_id', $_SESSION["id"]);

// Execute the statement
$stmt->execute();

// Fetch the results if needed

// Use $results as needed

    
    ob_start();
    ?>
    <?php
    form_debut("form", "POST", "reserver_trajet.php"); //on crée un formulaire pour recuperer le choix du trajet

    $donnees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($donnees!=FALSE){
        ?>

        <!--On crée un tableau contenant les infos des trajets trouvé   -->
        <table class='table table-hover'>
            <tr>
                <th>Ville de départ</th>
                <th>Ville d'arrivée</th>
                <th>Date</th>
                <th>Heure de départ</th>
                <th>Prix</th>
                <th>Places restantes</th>
                <th>Coche</th>
            </tr>
        <?php
        foreach ($donnees as $row) {
            echo"<tr>";
            echo"<th>" . $row["lieu_depart"] . "</th>";
            echo"<th>" . $row["destination"] . "</th>";
            echo"<th>" . $row["date"] . "</th>";
            echo"<th>" . $row["heure_dep"] . "</th>";
            echo"<th>" . $row["prix"] . "</th>";
            echo"<th>" . ($row["places_max"] - $row["places_prises"]) . "/" . $row["places_max"] . "</th>";
            
            //on crée un bouton radio qui renvoi l'id du trajet pour chaque trajet si il n'est pas complet
            if ($row["places_max"] != $row["places_prises"]) {
                echo"<th><input type='radio' name='choix_trajet' value='" . $row['id'] . "' /></th>";
            } else {
                echo'<th>COMPLET</th>';
            }
            echo"</tr>";
        }
        ?>

        </table>
        </br></br>
        <?php
        form_submit("Reserver", "Reserver", FALSE);
        form_fin();}
        else{echo"<div class='alert alert-danger'>Le trajet que vous cherchez n'est pas disponible .</div>";}
        ?>


    <?php
    return ob_get_clean();
}

$title = "Recherche trajet";
gabarit();

