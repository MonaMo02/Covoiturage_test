<?php
require '../config/BDD.php';
$bdd = getBdd();
require '../config/droits.php';  //voir inscription.php
require '../config/formulaire.php';
session_start();
// Check if the form is submitted
if ((isset($_POST["save"]))&&($_POST["save"]=="Save")) {
    

        // Update the user information in the database
        $username = $_SESSION["login"];
        $nom = $_POST["nom"];  // Update with the actual input field names
        $prenom = $_POST["prenom"];
        $matricule = $_POST["matricule"];
        $email = $_POST["email"];

        $query = "UPDATE user SET nom=?, prenom=?, matricule=?, email=? WHERE login=?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$nom, $prenom, $matricule, $email, $username]);
        $_SESSION['success_message'] = 'Changes saved successfully!';
        // Redirect to the profile page after saving changes
        header("Location: profil.php");  // Change to the actual profile page URL
        exit();
    }
elseif((isset($_POST["savecar"]))&&($_POST["savecar"]=="Save")) {
        $username = $_SESSION["id"];
        $marque = $_POST["marque"];  // Update with the actual input field names
        $modele = $_POST["modele"];
        $annee = $_POST["voiture_annee"];
        $couleur = $_POST["voiture_couleur"];

        $query = "UPDATE pilote SET voiture_marque=?, voiture_modele=?, voiture_couleur=?, voiture_annee=? WHERE pilote_user_id=?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$marque, $modele, $couleur, $annee, $username]);
        $_SESSION['success_message'] = 'Changes saved successfully!';
        // Redirect to the profile page after saving changes
        header("Location: profil.php");  // Change to the actual profile page URL
        exit();
}
?>
