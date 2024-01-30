<?php

require'../config/BDD.php';
$bdd = getBdd();

require '../config/droits.php';  //voir inscription.php
require '../config/formulaire.php';

test_membre_admin();
if (!isset($_GET["login"])) {                  //on peut afficher le profil d'un autre utilisateur par la méthode get avec comme variable l'username
    
	$username=$_SESSION["login"];  //on regarde donc si la page a été appelé par une methode GET, sinon on affiche le profil du membre qui accede à la page
}
else {
    $username=$_GET["login"];
}
ob_start();


$resultat=$bdd->query("SELECT * from user WHERE login ='".$username."'"); //on recupere les infos de l'user pour les afficher
$exist=0;
$donnee = $resultat->fetch(); //si la methode GET a été utilisée, on regarde si l'utilsateur existe bien afin d'afficher une erreur dans le cas inverse
$exist=1;
?>
  <div class="profile_page">
    <h1>A propos de vous</h1><hr>
    <div class="name_pic_card">
      <h3><?php echo ucfirst(strtolower($donnee["login"])); ?></h3>  
      <div class="userInfo">
      <script src='templates/js/verif_form.js'></script>
        <form  method="post"action="update_profile.php">
            <label for="nom">Nom : </label><input type="text" name ="nom"id="nom" value=<?php echo ucfirst(strtolower($donnee["nom"])); ?>  onchange=verifnom() disabled> <br>
            <label for="prenom">Preom : </label><input type="text" name ="prenom" id="prenom" value=<?php echo ucfirst(strtolower($donnee["prenom"])); ?> onchange=verifprenom() disabled> <br>
            <label for="matricule">Matricule : </label><input type="text" name ="matricule" id="matricule" value=<?php echo ucfirst(strtolower($donnee["matricule"])); ?> onchange=verifmatriculeetudiant() disabled> <br>
            <label for="email">Email : </label><input type="text" name ="email" id="email" value=<?php echo ucfirst(strtolower($donnee["email"])); ?> onchange=veriemail()  disabled> <br>
            <input type="submit" class="save" name="save" value="Save" style="visibility: hidden;">
          </form>    
      </div>
      <div class='profilePic'>
        <i class="fa-solid fa-user"></i>
      </div>
 
    
    <div class="profileButtons">
      <input type="button" class="enable" value="Modifier les informations personnelles" onclick="enableEdit()">       
    </div>
    <hr>
    <script>

      function enableEdit() {
          
            document.getElementById("nom").removeAttribute("disabled");
            document.getElementById("nom").style.borderBottom='1px #5b75728b solid';

            document.getElementById("prenom").removeAttribute("disabled");
            document.getElementById("prenom").style.borderBottom='1px #5b75728b solid';

            document.getElementById("matricule").removeAttribute("disabled");
            document.getElementById("matricule").style.borderBottom='1px #5b75728b solid';

            document.getElementById("email").removeAttribute("disabled");
            document.getElementById("email").style.borderBottom='1px #5b75728b solid';

            document.querySelector('.save').style.visibility = 'unset';
      }
     
    </script>
  </div>
<?php 
  $resultat2=$bdd->query("SELECT * from pilote WHERE pilote_user_id =".$donnee["id"]); 
  //on teste si c'est un pilote, si oui on affiche les infos de la voiture
  $donnee2 = $resultat2->fetch();    
  if(!empty($donnee2)){     
?>

<div class="car"> 
  <h2>Vehicule</h2>           
  <div class="carInfo"> 
    <form  method="post" action="update_profile.php">
        <label for="marque">Marque :  </label><input type="text" name="marque" id="marque" value=<?php echo ucfirst(strtolower($donnee2["voiture_marque"])); ?>  onchange=verifmarque()> <br>
        <label for="modele">Modele :  </label><input type="text" name="modele" id="modele" value=<?php echo ucfirst(strtolower($donnee2["voiture_modele"])); ?> disabled onchange=verifmodele()> <br>
        <label for="voiture_annee">Annee : </label><input type="text" name="voiture_annee" id="voiture_annee" value=<?php echo ucfirst(strtolower($donnee2["voiture_annee"])); ?> disabled onchange=verifannee()> <br>
        <label for="voiture_couleur">Couleur : </label><input type="text" name="voiture_couleur" id="voiture_couleur" value=<?php echo ucfirst(strtolower($donnee2["voiture_couleur"])); ?> disabled onchange=verifcouleur()> <br>
        <input type="submit" name="savecar" class="savecar" value="Save"  style="visibility: hidden;">
    </form>
  </div> 
  <div class="carButtons">
      <input type="button" class="enablecar" value="Modifier les informations de la vehicule" onclick="enableEditcar()"> 
  </div>  
</div>          
  <script>
      function enableEditcar() {
    
            document.getElementById("marque").removeAttribute("disabled");
            document.getElementById("marque").style.borderBottom='1px #5b75728b solid';

            document.getElementById("modele").removeAttribute("disabled");
            document.getElementById("modele").style.borderBottom='1px #5b75728b solid';

            document.getElementById("voiture_annee").removeAttribute("disabled");
            document.getElementById("voiture_annee").style.borderBottom='1px #5b75728b solid';

            document.getElementById("voiture_couleur").removeAttribute("disabled");
            document.getElementById("voiture_couleur").style.borderBottom='1px #5b75728b solid';

            document.querySelector('.savecar').style.visibility = 'unset';
      }    
    </script>
<?php   
     
}

                            
// if($exist==0){
//      echo"L'utilisateur n'existe pas";
// }

$contenu=ob_get_clean();   
   
   
$title = "Profil";
gabarit();

