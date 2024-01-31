<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require'../config/BDD.php';
$bdd = getBdd();

require '../config/droits.php';        //voir inscription.php pour commentaires
require '../config/formulaire.php';

test_membre();
ob_start();
    echo "<div class='monCompte'  style='margin-top:100px;'>";
    echo "<h1>Mes compte</h1>";
    //on crée un tableau contenant les transactions dans lequel l'utilisateur est impliqué
    echo "<table class='table table-hover'><tr><th style='width: 30%; text-align:center;'>Utilisateur</th><th style='width: 20%; text-align:center;'>Debit</th><th style='width: 20%; text-align:center;'>Credit</th></tr>";
    $reponse = $bdd->query("SELECT * FROM transaction WHERE credit_user_id = " . $_SESSION["id"]." OR debit_user_id = ". $_SESSION["id"]);
    while($donnee = $reponse->fetch()){
    if($donnee["credit_user_id"]==$_SESSION["id"]){ //on regarde si dans la transaction l'utilisateur est au credit   
    $reponse2 = $bdd->query("SELECT prenom, nom FROM user WHERE id = " . $donnee["debit_user_id"]);
    $credit=$donnee["somme"];
    $debit="";
    }
    else {//ou si il est au debit
    $reponse2 = $bdd->query("SELECT prenom, nom FROM user WHERE id = " . $donnee["credit_user_id"]);  
    $debit = $donnee["somme"];
    $credit="";
    }
    $donnee2 = $reponse2->fetchAll();
    echo"<tr><td style=' text-align:center;' >".$donnee2[0]["prenom"]." ".$donnee2[0]["nom"]."</td><td style=' text-align:center;'>".$debit."</td><td style=' text-align:center;'>".$credit."</td></tr>";
    
    }
    echo"</table>";
    $reponse = $bdd->query("SELECT compte FROM user WHERE id = " .$_SESSION["id"]);
    $donnee = $reponse->fetchAll();  //on affiche la variable compte de la table user qui contient le total debit credit
    echo"<h3 style=' text-align:center; color: #14525c; '><b>Total :</b> ".$donnee[0]["compte"]. "$". "</h3>";

 $contenu=ob_get_clean();   
   
   
$title = "Mon compte";
gabarit();