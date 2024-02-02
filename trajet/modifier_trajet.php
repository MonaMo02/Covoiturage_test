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

    header('Location: ../index.php');
    exit();
}


$contenu = formulaire();

$trajet_id = $_POST["modif"]; //on recupere l'id du trajet à supprimer
// Fetch existing values for this trajet_id from the database
$sql2 = "SELECT * FROM trajet WHERE id = :trajet_id";
$statement = $bdd->prepare($sql2);
$statement->execute(array(":trajet_id" => $trajet_id));
$existingValues = $statement->fetch(PDO::FETCH_ASSOC);


ob_start();

// Fetching passenger details to inform about the trajet chagement
$reponse = $bdd->query("SELECT user_id, prenom, nom FROM trajet_passager, user WHERE trajet_id = " . $trajet_id . " AND id=user_id");
while ($donnee = $reponse->fetch()) {
    echo "Le trajet a été modifié. Veuillez vérifier les détails mis à jour.<br><br>";

    // Send a notification to passengers about the trip modification
    $sql = 'INSERT INTO messagerie (expediteur_user_id, destinataire_user_id, titre, message, date)
           VALUES(:expediteur_user_id, :destinataire_user_id, :titre, :message, :date)';

    $statement = $bdd->prepare($sql);
    $statement->execute(array(
        ":destinataire_user_id" => $donnee["user_id"],
        ":expediteur_user_id" => $_SESSION["id"],
        ":message" => "Cher " . $donnee["prenom"] . " " . $donnee["nom"] . ", le trajet a été modifié. Veuillez vérifier les détails mis à jour.",
        ":titre" => "Modification du trajet",
        ":date" => date('Y-m-d H:i:s')
    ));
}
function formulaire()
{
    ob_start();

    $bdd = getBdd();
    $trajet_id = $_POST["modif"]; //on recupere l'id du trajet à modif

    // Fetch existing values for this trajet_id from the database
    $sql2 = "SELECT * FROM trajet WHERE id = :trajet_id";
    $statement = $bdd->prepare($sql2);
    $statement->execute(array(":trajet_id" => $trajet_id));
    $existingValues = $statement->fetch(PDO::FETCH_ASSOC); ?>
    <h1 style="margin-top:100px;" id="ajttrajetheader">Modification de votre trajet</h1>
    <div class="publier_trajet">
        <?php
        form_debut("form", "POST", "save_modif_trajet.php"); ?>
        <h2>Départ: <?= $existingValues["lieu_depart"] ?> </h2>
        <br>

        <h2>arrivée: <?= $existingValues["destination"] ?></h2>
        <div id="autocomplete-suggestions-destination"></div>
        <div id="result"></div>
        <script src="../templates/js/geolocation.js"></script>

        <input type="time" name="heure" value="<?= $existingValues["heure_dep"] ?>" id="heure" required class="datetimeTRJ">
        <input type="date" name="date" value="<?= $existingValues["date"] ?>" id="date" required class="datetimeTRJ"><br>
        <script>
            document.getElementById('date').setAttribute('min', new Date().toISOString().split('T')[0]);
            document.getElementById('date').setAttribute('value', new Date().toISOString().split('T')[0]);
        </script>
        <br>
        <input type="number" name="places" id="places" value="<?= $existingValues["places_max"] ?>" placeholder="2 places" min="0" max="5">
        <input type="text" name="prix" id="prix" value="<?= $existingValues["prix"] ?>">

        <input type='hidden' name='trajet_id' id='trajet_id' value=' $trajet_id'>
        <input type='hidden' name='trajet_id' id='trajet_id' value=' $trajet_id'>
        <?php
        form_submit("Soumettre", "Soumettre", FALSE);
        form_reset("reset", "", FALSE, FALSE);
        echo "<br>";
        form_fin();
        ?>
    </div>
<?php
    return ob_get_clean();
}

$title = "Modification trajet";
gabarit();
?>