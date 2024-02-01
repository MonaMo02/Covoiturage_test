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
        <link href="../templates/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <script type="text/javascript" src="../templates/js/verif_form.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!--[if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
       
        <link href="../templates/css/style2.css" rel="stylesheet">

        <style>
        /* Add your custom styles here */
        .custom-card {
            /* Add styles similar to the navbar */
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            margin-left: 70px;
        }

        .custom-card h4 {
            /* Style the heading inside the card */
            color: #333;
        }
    </style>

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
                <nav class="collapse navbar-collapse" role="navigation">
                    
                    <ul class="nav navbar-nav navbar-right">
                        <p class="navbar-btn">
                            <a href="../membre/deconnexion.php" class="btn btn-large"><i class="glyphicon glyphicon-off"></i> Deconnexion</a>
                        </p>
                    </ul>
                </nav>
            </div>
        </nav>
            
        </div><!--/container-->

    <!--main-->
    <div class="container">
        <div class="row">
            <?php echo $contenu2; ?>
        </div> 
        <div class="row">
            <?php echo $contenu; ?>
        </div>
    </div><!--/container-->



    <!-- script references -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="../templates/js/bootstrap.min.js"></script>
    <script src="../templates/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>