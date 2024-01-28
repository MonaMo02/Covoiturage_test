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
    <body>
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

        
    <!--main-->
    <div class="container">
        
            <div class="col-md-11">
                <?php echo $contenu; ?>

            </div>
        
    </div>

    
    <!-- script references -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="../templates/js/bootstrap.min.js"></script>
    <script src="../templates/js/scripts.js"></script>
</body>
</html>