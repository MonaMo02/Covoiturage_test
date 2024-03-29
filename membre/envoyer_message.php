<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require'../config/BDD.php';
$bdd = getBdd();             //voir inscription.php
require '../config/formulaire.php';
require '../config/droits.php';

test_membre();
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Envoyer') {
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['titre']) && !empty($_POST['titre'])) && (isset($_POST['message']) && !empty($_POST['message']))) {
        $contenu = action();
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();
}

function formulaire() {
    global $bdd;
    ob_start();
    ?>
    <div class="envMsg" style='margin-top:100px'>
  
        <h1  >Ecrire un message</h1>
        <div class="formMsg">
        <?php
        form_debut("form", "POST", "envoyer_message.php");      //on crée le formulaire pour que l'utilisateur puisse saisir le message

            //on regarde si la page a été appelé depuis un autre formulaire POST(mes trajets) qui contient le login du destinataire si oui on ne laisse pas le choix à l'utilisateur
            if (isset($_POST["destinataire"])) {
                form_hidden("destinataire_username", $_POST["destinataire"]);
                echo"<h4 id='desti' > <b>Destinataire :</b> " . $_POST["destinataire"] . "</h4>";
            } else {
                //sinon on fait une liste déroulante contenant tous les usernames des membres comme destinataire
                form_label("Destinataire"); 
                form_select_sql_attribut_user("Destinataire :", "destinataire_username", 1, $bdd, "login", "user");
            }
            echo"<br>";
            
            form_input_text("titre", TRUE, "titre", "", 48, "");
            echo"<br><br>";
            
            form_textarea("message", 10, 45, "Message", TRUE, "");
            echo "<br><br>";
            form_submit("Soumettre", "Envoyer", FALSE);
            
            form_fin();
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function action() {

    global $bdd;
    $reponse = $bdd->query("SELECT id FROM user WHERE login = '" . $_POST["destinataire_username"] . "'");
    $donnee = $reponse->fetchAll(); //on recupere l'id du destinataire
    $destinataire_id = $donnee[0]["id"];
    $sql = 'INSERT INTO messagerie (titre,date,message,expediteur_user_id,destinataire_user_id) VALUES(:titre,:date,:message,:expediteur_id,:destinataire_id)';
    $statement = $bdd->prepare($sql); 
    $statement->execute(array(
        ":titre" => $_POST["titre"],
        ":date" => date('Y-m-d H:i:s'),//la fonction date permet d'envoyer la date au moment où l'utilisateur envoi le message
        ":message" => $_POST["message"],         //on insere le message dans la table messagerie
        ":expediteur_id" => $_SESSION["id"],
        ":destinataire_id" => $destinataire_id
    ));

    return "<div style='margin-top:100px' class='alert alert-success'>Le message a bien été envoyé.</div>";
}

$title = "Ecrire message";
gabarit();

