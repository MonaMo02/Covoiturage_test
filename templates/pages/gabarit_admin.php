<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" type="image/png" href="../covoiturage.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" /><![endif]-->
        <link href="../templates/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <script type="text/javascript" src="../templates/js/verif_form.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!--[if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
       
        <link href="../templates/css/style2.css" rel="stylesheet">
        <link href="../templates/css/adminStyle.css" rel="stylesheet">


    </head>
    <body>
    <nav class="navbar navbar-default navbar-fixed-top costum-navbar-style" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="#" class="navbar-brand innernav"><span class = "logonavbar">CarFetch</span></a>
                </div>
                <nav class="collapse navbar-collapse innernav" role="navigation">

                    <ul class="nav navbar-nav navbar-right costum-nava-style">
                        <p class="navbar-btn costum-p-navbar-buttons">
                            <a href="../membre/deconnexion.php" class="btn  btn-large insc-button " > Deconnexion</a>
                        </p>
                    </ul>
                </nav>
            </div>
        </nav>

            

    <!--main-->

    <!-- <div class="container " style = "margin-top : 100px; margin-left: 150px; align-item : center;">
        
    </div> -->
    <div class="container" style = "margin-top: 100px;">
    <div class="row " style = "margin-left: 80px;">
            <?php echo $contenu2; ?>
        </div> 
        <div class="row"  style = " display : flex ;">
            <?php echo $contenu; ?>
        </div>

        <div>
            <?php  echo $contenu3; ?>
        </div>
    </div><!--/container-->



    <!-- script references -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="../templates/js/bootstrap.min.js"></script>
    <script src="../templates/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>