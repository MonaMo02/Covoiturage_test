<!DOCTYPE html>
<html lang="en">
    <!-- <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><?php echo $title; ?></title> 
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" type="image/png" href="../favicon.png" />
        [if IE]><link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" /><![endif]
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="../templates/css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../templates/js/verif_form.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


        [if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]
        <link href="../templates/css/styles.css" rel="stylesheet">

    </head> -->


    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Bienvenue</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
        <link href="templates/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="https://kit.fontawesome.com/e3b74a388e.js" crossorigin="anonymous"></script>
        <!-- <link href="templates/css/styles.css" rel="stylesheet"> -->
        <link href="./templates/css/style2.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="banner">
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
                <ul class="nav navbar-nav navbar-right">
                    <p class="navbar-btn">
                        <a href="../membre/deconnexion.php" class="btn btn-danger btn-large"><i class="glyphicon glyphicon-off"></i> Deconnexion</a>
                    </p>
                </ul>
            </div>
        </nav>

            <div class="container">
                <?php echo $contenu2; ?>
            </div> 
        </div><!--/container-->

    <!--main-->
    <div class="container">
        <div class="row">

                <?php echo $contenu; ?>

        </div>
    </div><!--/container-->



    <!-- script references -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="../templates/js/bootstrap.min.js"></script>
    <script src="../templates/js/scripts.js"></script>
</body>
</html>