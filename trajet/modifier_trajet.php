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
    <h1 style="margin-top:100px;" id="modtrajetheader">Modification des informations de votre trajet</h1>
    <div class="mdf_trajet">
        <?php
        form_debut("form", "POST", "save_modif_trajet.php"); ?>
        <div >
            <div class="lieux">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#14525c" class="bi bi-geo-alt" viewBox="0 0 16 16">
                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
                <p><?= $existingValues["lieu_depart"] ?></p>
                
            </div>
            <div class="lieux">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#14525c" class="bi bi-geo-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.3 1.3 0 0 0-.37.265.3.3 0 0 0-.057.09V14l.002.008.016.033a.6.6 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.6.6 0 0 0 .146-.15l.015-.033L12 14v-.004a.3.3 0 0 0-.057-.09 1.3 1.3 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465s-2.462-.172-3.34-.465c-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411" />
                </svg>
                <p><?= $existingValues["destination"] ?></p>
            </div>
        </div>
        <div class="mdf_inputs">
            <input type="time" name="heure" value="<?= $existingValues["heure_dep"] ?>" id="heure" required class="datetimeTRJ">
            <input type="date" name="date" value="<?= $existingValues["date"] ?>" id="date" required class="datetimeTRJ"><br>
            <script>
                document.getElementById('date').setAttribute('min', new Date().toISOString().split('T')[0]);
                document.getElementById('date').setAttribute('value', new Date().toISOString().split('T')[0]);
            </script>
            <br>
            <input type="number" name="places" id="places" value="<?= $existingValues["places_max"] - $existingValues["places_prises"] ?>" min="0" max="5">
            <input type="text" name="prix" id="prix" value="<?= $existingValues["prix"] ?>">

            <input type="hidden" name="modif" id="trajet_id" value="<?= $trajet_id ?>">

            <?php
            form_submit("Soumettre", "Soumettre", FALSE);
            form_reset("reset", "", FALSE, FALSE);
            echo "<br>";
            form_fin();
            ?>
        </div>
    </div>
<?php
    return ob_get_clean();
}

$title = "Modification trajet";
gabarit();
?>