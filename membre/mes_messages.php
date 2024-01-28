<?php

require'../config/BDD.php';
$bdd = getBdd();

require '../config/droits.php';
require '../config/formulaire.php';

test_membre();
ob_start();$bdd = getBdd();
$resultat = $bdd->query("SELECT * FROM messagerie AS M, user AS U WHERE M.destinataire_user_id = " . $_SESSION["id"] . " AND U.id = M.expediteur_user_id ORDER BY M.id DESC");
echo "<div class='mes_messages' style='margin-top:100px'>";
echo "<h1 '>Boîte de réception</h1>";
$messages = $resultat->fetchAll(PDO::FETCH_ASSOC);

if (!empty($messages)) {
    

    foreach ($messages as $index => $donnee) {
        $tab_date = explode(" ", $donnee["date"]);
        $tab_jour = explode("-", $tab_date[0]);

        echo '<form action="envoyer_message.php" method="post">';
        echo "<div class=\"panel panel-success cont \">";
        echo "<div class=\"msgcard\">";
        echo "<h4><b>Expediteur :</b> " . $donnee["prenom"] . " " . $donnee["nom"] . "</h4>";
        echo "<h4><b>Titre : </b>" . $donnee["titre"] . "</h4>";
        echo "<p>Le " . $tab_jour[2] . " " . $tab_jour[1] . " " . $tab_jour[0] . " à " . $tab_date[1] . "</p>";
        
        echo "<button class=\"btn  pull-right  msgbutton\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapseExample" . $index . "\" aria-expanded=\"false\" aria-controls=\"collapseExample\">";
        echo "Lire le message";
        echo "</button>";
        echo "<button type='submit' name='destinataire' class='btn pull-right msgbutton' value='" . $donnee["login"] . "'>Répondre</button></form>";
        echo "</div>";
        echo "<div class=\"collapse\" id=\"collapseExample" . $index . "\">";
        echo "<div class=\"well\">";
        echo "<div class=\"panel-body\">";
        try {
            
            echo "<p>" . $donnee["message"] . "</p>";
            echo "<br>";
        } catch (PDOException $e) {
            echo 'Échec : ' . $e->getMessage();
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<h4 class='emptyMsg'>Pas de messages pour le moment. Réservez ou publiez un trajet pour contacter d’autres membres. Si vous avez déjà un trajet de prévu, n’hésitez pas à contacter les personnes avec qui vous voyagez !</h4>";
}
echo "</div";
$contenu = ob_get_clean();

$title = "Mes messages";
gabarit();

