<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require '../config/BDD.php'; //on inclut le fichier qui contient les infos sur la BDD puis on le met dans la variable bdd à l'aide de la fonction getBdd
$bdd = getBdd();

require '../config/formulaire.php'; //on inclut les fonctions de formulaires pour construire rapidement le formulaire de la page
require '../config/droits.php'; //on inclut les fonctions qui permettent de tester et de vérifier qui peut accéder à cette page
test_visiteur(); //on teste si c'est un visiteur si ce n'en est pas un on le redirige vers l'index
// on teste si le visiteur a soumis le formulaire

if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Soumettre') {
    //on met dans une chaine le type de l'image soumise
   
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['password']) && !empty($_POST['password'])) && (isset($_POST['prenom']) && !empty($_POST['prenom'])) && (isset($_POST['nom']) && !empty($_POST['nom'])) && (isset($_POST['matricule']) && !empty($_POST['matricule'])) && (isset($_POST['email']) && !empty($_POST['email'])) && (isset($_POST['num_tel']) && !empty($_POST['num_tel'])) ) {
        $sql = 'SELECT count(*) FROM user WHERE login="' . $_POST['login'] . '"OR email="' . $_POST['email'] . '"';
        $reponse = $bdd->query($sql);
        $donnee = $reponse->fetch();        //on regarde si il existe deja l'email ou le login soumis
        if ($donnee[0] == 0) {
            $contenu = action();   //si ils n'existent pas alors on appelle la fonction action qui va inscrire l'utilisateur
        } else {

            $contenu = "Login ou adresse email déjà utilisé(e)";
        }
    }  else {
        $contenu = "<div class='alert alert-error'>Certains champs ne sont pas remplis</div>";
    }
} else {
    $contenu = formulaire(); //si le formulaire n'est pas soumis alors cela veut dire que le visiteur vient d'arriver et on affiche le formulaire
}

function formulaire() {
    ob_start(); //on met en memoire tampon le code hmtl qui va suivre
    ?>
    
    <div class='inscription'>
        <h1 style='color: #14525c; font-size:42px; margin-left:140px;'>Inscrivez-vous</h1>
    <?php

    echo"<script src='/covoiturage_test/templates/js/verif_form.js'></script>";
    form_debut("form", "POST", "inscription.php");
    
    form_input_text("nom", TRUE, "Nom", "", 30, "verifnom();");
    echo"<br><br>";
    form_input_text("prenom", TRUE, "Prenom", "", 30, "verifprenom();");  //on fait notre formulaire en utilisant les fonctions de formulaire.php
    echo"<br><br>";
    form_input_text("login", TRUE, "username", "", 30, "veriflogin();");
    echo"<br><br>";
    form_input_text("num_tel", TRUE, "numéro de téléphone", "", 30, "verifnumtel();");
    echo"<br><br>";
    
    form_input_text("matricule", TRUE, "Mratricule", "", 30, "verifmatriculeetudiant();");
    echo"<br><br>";
    
    form_input_email("email", TRUE, "Email", "", 45, "verifemail();");
    echo"<br><br>";
    
    form_input_mdp("password", TRUE, "Mot de passe", "", 30, "verifpassword();");
    echo "<span style=\"color: red;\" id=\"lvlsecure\"></span>";
    echo"<br><br>";
    
    form_submit("Soumettre", "Soumettre", FALSE);
    
    form_fin();
    ?>
    <p style="margin-left: 170px">already have an accounet? <a href="../membre/connexion.php">sign up</a></p>
    </div>
    <?php
    return ob_get_clean(); //la fonction retourne tout le html qui a été mis en tampon.
}

function action() {
  //  $chemin_destination = '../photo_profil/'; //chemin pour stocker les photos de profil use it for voiture
   // move_uploaded_file($_FILES["pic"]['tmp_name'], $chemin_destination . $_POST["login"] . strrchr($_FILES['pic']['name'], '.')); //on met la photo dans le dossier
    global $bdd;
    $sql = 'INSERT INTO user (password, login, nom, prenom, email, num_tel, matricule,compte) VALUES(:password,:login,:nom,:prenom,:email,:num_tel,:matricule,:compte);';
    $statement = $bdd->prepare($sql);
    $result = $statement->execute(array(
        ":password" => md5($_POST["password"]),
        ":login" => $_POST["login"],
        ":nom" => $_POST["nom"],
        ":prenom" => $_POST["prenom"],
        ":email" => $_POST["email"],
        ":num_tel" => $_POST["num_tel"],
        ":matricule" => $_POST["matricule"],
        ":compte" => "0",
      //  ":photo" => $chemin_destination . $_POST["login"] . strrchr($_FILES['pic']['name'], '.'),       //on inscrit l'utilisateur dans la base de données
        
    ));

    if($result){
    return "<div class='alert alert-success'>Inscription réussie.</div>";
    }
    else{
        return "<div class='alert alert-success'>Inscription échouée.</div>";
    } //on retourne que l'inscritpion a été reussi
}

$title = "Inscription";
gabarit(); //la fonction gabarit va afficher la page du bon gabarit, donc ici visiteur avec comme contenu ce qui est dans la variable $contenu et comme titre la variable $title

