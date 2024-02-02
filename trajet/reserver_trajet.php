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
    $reponse = $bdd->query("SELECT * FROM trajet as T, user as U, pilote as P WHERE T.id = " . $choix_trajet." AND U.id= T.pilote_user_id AND P.pilote_user_id = T.pilote_user_id And places_prises<places_max");
    $donnee = $reponse->fetch();
    $tab_places = range(0, $donnee["places_max"] - $donnee["places_prises"]);

    ob_start();
    ?>
    <h3 id="hd3">Reserver votre trajet</h3>
    
    
    <?php
    if ($donnee ==true) {

        
        $sql = "
        SELECT  t.lieu_depart, t.destination, t.places_max, t.places_prises,
                CONCAT(u.nom, ' ', u.prenom) AS nom_prenom,
                CONCAT(p.voiture_marque, ' ', p.voiture_modele) AS marque_modele,
                t.date,  t.heure_dep, t.prix
    FROM 
        trajet t
    INNER JOIN 
        pilote p ON t.pilote_user_id = p.pilote_user_id
    INNER JOIN 
        user u ON p.pilote_user_id = u.id
        ";


    $stmt = $bdd->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC)?>
        <div class='journey-container'>
        <script src="https://kit.fontawesome.com/e3b74a388e.js" crossorigin="anonymous"></script>
        
                                <div class="journey-item">                            
                                    <i class="fa-solid fa-location-pin tp"></i>
                                    <div class='line'>
                                        <div class="journey-info">
                                            <div class="info-section">
                                                <span class="info-label"></span>
                                                <span class="info-value"><?php echo trim(explode(",", $row["lieu_depart"])[0]);?></span>
                                            </div>
                                            <div class="info-section indexprice ">
                                                <span class="info-label"><i class="fa-solid fa-dollar-sign "></i></span>
                                                <span class="info-value prix"><?php echo $row["prix"]; ?></span>
                                            </div>
                                        </div>
                                    
                                        <div class="journey-info">
                                            <div class="info-section">
                                                <span class="info-label"></span>
                                                <span class="info-value"><?php echo trim(explode(",", $row["destination"])[0]); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <i class="fa-solid fa-location-pin btm"></i>
                                    
                                    <div class="divider"></div> 
                                        <img width="70px" style=" border-radius: 50px; margin-left:5px; margin-top:-10px; position:absolute;" src="templates/image/driver.jpg" alt="">
                                        
                                        <div class="pilote-info">            
                                            <span class="pilote-name"><?php echo $row["nom_prenom"]; ?></span> <br>
                                            <span class="car-value"><?php echo $row["marque_modele"]; ?></span>
                                        </div>
                                        <form action="reserver_trajet.php" method="post">
                                        <input type="submit" class="resbutton" value="Reserver">
                                        <select calss="select-places" name ="nb_places" id="nb_places" size=1 <?php foreach ($tab_places as $elem_hash) {
                                        echo("<option>" . $elem_hash . "</option>"); } ?>  disabled></select>
                                        </form>
                                    </div>
                                    </div>
                                                                    
                                    
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

        $bdd->exec("UPDATE trajet SET places_prises = places_prises + " . $new_places . " WHERE id = " . $choix_trajet);
    //    $bdd->exec("UPDATE trajet SET effectue = 1 WHERE id = " . $choix_trajet);
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

