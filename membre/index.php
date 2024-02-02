<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$title="Accueil";          //ceci est la page index du membre, lorsque un membre connecté va sur la page index du site il est directement redirigé ici.

require '../config/BDD.php';
$bdd=  getBdd();
session_start(); //on demarre la session
if (isset($_SESSION['login'])) { //on verifie que l'utilisateur est bien connecté
ob_start();
?>

        
<h1 >Bienvenue <?php echo$_SESSION['prenom']." ".$_SESSION['nom'];?></h1>
<?php 
$sql = "SELECT count(*) FROM messagerie WHERE destinataire_user_id=".$_SESSION['id']; //on recuêre le nombre de message recu par l'utilisateur
        $reponse = $bdd->query($sql);
        $donnee = $reponse->fetch();
$sql2 = "SELECT count(*) FROM trajet as T, trajet_passager as TP WHERE TP.trajet_id=T.id AND T.effectue=0 AND (T.pilote_user_id=".$_SESSION["id"]." OR TP.user_id=".$_SESSION["id"].")"; 
        $reponse2 = $bdd->query($sql2);  //on recupere le nombre de trajets en cours auquel l'utilisateur est lié
        $donnee2 = $reponse2->fetch();
?>
<!-- On affiche les deux infos precedentes -->
<div style = "display : flex;">
<div>

<h3><span class="fa-stack " style="vertical-align: middle; margin-right:15px;"><i class="fa fa-envelope-square fa-stack-2x" ></i></span>  Vous avez <?php echo $donnee[0];?> messages.</h3>
<h3><span class="fa-stack " style="vertical-align: middle; margin-right:15px;"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-car fa-stack-1x fa-inverse"></i></span>  Vous êtes actuellement sur <?php echo $donnee2[0];?> trajets.</h3>  

</div>
<div>
<img src="../28910691_7506747.svg" alt="SVG Image" width="550" height="550">

</div>

</div>
<?php $contenu = ob_get_clean();
if($_SESSION["pilote"]==TRUE){
require '../templates/pages/gabarit_pilote.php';  //on choisit le gabarit on fonction si c'est un pilote ou passager
}
else{
require '../templates/pages/gabarit_passager.php';  
}

}
else{
        header ('Location: ../index.php'); //si ce l'utilisateur n'est pas connecté on le redirige vers l'index du site
        exit();
}?>  

                

