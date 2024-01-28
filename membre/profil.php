<?php

require'../config/BDD.php';
$bdd = getBdd();

require '../config/droits.php';  //voir inscription.php
require '../config/formulaire.php';

test_membre_admin();
if (empty($_GET)) {                  //on peut afficher le profil d'un autre utilisateur par la méthode get avec comme variable l'username
    
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
        <form  method="post">
            <label for="nom">Nom : </label><input type="text" id="nom" value=<?php echo ucfirst(strtolower($donnee["nom"])); ?> disabled> <br>
            <label for="prenom">Preom : </label><input type="text" id="prenom" value=<?php echo ucfirst(strtolower($donnee["prenom"])); ?> disabled> <br>
            <label for="matricule">Matricule : </label><input type="text" id="matricule" value=<?php echo ucfirst(strtolower($donnee["matricule"])); ?> disabled> <br>
            <label for="email">Email : </label><input type="text" id="email" value=<?php echo ucfirst(strtolower($donnee["email"])); ?> disabled> <br>
        </form>    
      </div>
      <div class='profilePic'>
        <i class="fa-solid fa-user"></i>
      </div>
      <div class="profileTotalMoney">
        <?php
          $reponse = $bdd->query("SELECT compte FROM user WHERE id = " .$_SESSION["id"]);
          $donnee5 = $reponse->fetchAll();  //on affiche la variable compte de la table user qui contient le total debit credit
        echo"<h5><b>Credit :</b> ".$donnee5[0]["compte"]." $" ."</h5>";
        ?>
      </div>
    </div>
    
    <div class="profileButtons">
      <input type="button" class="enable" value="Modifier les informations personnelles" onclick="enableEdit()">  
      <input type="button" class="save" value="Save changes"  style="visibility: hidden;">
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
    <form  method="post">
        <label for="marque">Marque :  </label><input type="text" id="marque" value=<?php echo ucfirst(strtolower($donnee2["voiture_marque"])); ?> disabled> <br>
        <label for="modele">Modele :  </label><input type="text" id="modele" value=<?php echo ucfirst(strtolower($donnee2["voiture_modele"])); ?> disabled> <br>
        <label for="voiture_annee">Annee : </label><input type="text" id="voiture_annee" value=<?php echo ucfirst(strtolower($donnee2["voiture_annee"])); ?> disabled> <br>
        <label for="voiture_couleur">Matricule : </label><input type="text" id="voiture_couleur" value=<?php echo ucfirst(strtolower($donnee2["voiture_couleur"])); ?> disabled> <br>
    </form>
  </div> 
  <div class="carButtons">
      <input type="button" class="enablecar" value="Modifier les informations de la vehicule" onclick="enableEditcar()"> 
      <input type="button" class="savecar" value="Save changes"  style="visibility: hidden;">
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

