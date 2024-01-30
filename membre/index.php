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
ob_start();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" type="image/png" href="../favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" /><![endif]-->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="../templates/css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../templates/js/verif_form.js"></script>
        <script src="https://kit.fontawesome.com/e3b74a388e.js" crossorigin="anonymous"></script>

        <!--[if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="../templates/css/style2.css" rel="stylesheet">

    </head>
    <nav class="navbar navbar-default navbar-fixed-top" role="banner" style="background-color:white; height:50px;">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="../index.php" class="navbar-brand"><span class = "logonavbar">CARPE DIEM</span></a>
                </div>
                <nav class="collapse navbar-collapse" role="navigation">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="../membre/profil.php">Profil</a>
                        </li>
                        <li>
                            <a href="../membre/mon_compte.php">Mes comptes</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle"  data-toggle="dropdown">Messagerie<b class="caret"></b></a>
                            <ul class="dropdown-menu" style="background-color:white;" >
                                <li><a href="../membre/mes_messages.php">Mes Messages</a></li>
                                <li><a href="../membre/envoyer_message.php">Nouveau message</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="../trajet/mes_trajets.php">Mes trajets</a>
                        </li>
                        <!-- <li><a href="../trajet/recherche_trajet.php">Rechercher un trajet</a></li> -->
                        <li><a href="../trajet/ajout_trajet.php">Ajouter un trajet</a></li>
                        <li><a href="../trajet/recherche_trajet.php">Rechercher un trajet</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <p class="navbar-btn">
                            <a href="../membre/deconnexion.php" class="btn btn-large"><i class="glyphicon glyphicon-off"></i> Deconnexion</a>
                        </p>
                    </ul>
                </nav>
            </div>
        </nav>
        <div class="container">
        
                <div class="col-md-11">
        
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

                        <h2><span class="fa-stack " style="vertical-align: middle; margin-right:15px;"><i class="fa fa-envelope-square fa-stack-2x" ></i></span>  Vous avez <?php echo $donnee[0];?> messages.</h2>
                        <h2><span class="fa-stack " style="vertical-align: middle; margin-right:15px;"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-car fa-stack-1x fa-inverse"></i></span>  Vous êtes actuellement sur <?php echo $donnee2[0];?> trajets.</h2>  
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

                </div>

        </div> 
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="../templates/js/bootstrap.min.js"></script>
    <script src="../templates/js/scripts.js"></script>
</body>
</html>
