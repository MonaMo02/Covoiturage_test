<?php

session_start();
$title="Accueil";          //ceci est la page index du membre, lorsque un membre connecté va sur la page index du site il est directement redirigé ici.

require './config/BDD.php';
$bdd=  getBdd();
if (isset($_SESSION['login'])) {          //si c'est un membre qui est connecté alors on le renvoi à la page index membre
    header('Location: membre/index.php');
    exit();
}

?>

<!-- On utilise le framework bootstrap pour génerer le html et CSS afin d'avoir une esthétique moderne mais qui ne prend pas trop de temps à réaliser".-->
<!DOCTYPE html>
<html lang="en">
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
        <nav class="navbar navbar-default navbar-fixed-top costum-navbar-style" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="#" class="navbar-brand innernav"><span class = "logonavbar">CARFETCH</span></a>
                </div>
                <nav class="collapse navbar-collapse innernav" role="navigation">

                    <ul class="nav navbar-nav navbar-right costum-nava-style">
                        <p class="navbar-btn costum-p-navbar-buttons">
                            <a href="membre/connexion.php" class="btn  btn-large insc-button " > Connexion</a>
                            <a href="inscription/inscription.php" class="btn  btn-large insc-button"> Inscription</a>
                        </p>
                    </ul>
                </nav>
            </div>
        </nav>
        
        <div class="containerbody">                  
            <div class="quick-search-form"  style="margin-top: 340px;"> 
                <form id="searchForm"  method="POST">
                    <input type="text" name="ville_depart" class='start input' placeholder='Depart' required/>
                    <input type="text" name="ville_arrivee" class='dest input' placeholder='Destination' required />
                    <input type="date" id="myDate" class="date input" name="date" min="yyyy-mm-dd" placeholder="Select a date">
                    <input type="number" name="nbseat" class='nbseat input' placeholder='0' />
                    <input type="submit" value="Search" class="submit-search">
                </form>

                
            </div>
            </div>

                <script>
                    document.getElementById('myDate').setAttribute('min', new Date().toISOString().split('T')[0]);
                    document.getElementById('myDate').setAttribute('value', new Date().toISOString().split('T')[0]);

 
                    $(document).ready(function() {
                        $('#searchForm').submit(function(event) {
                            event.preventDefault(); 
                            var formData = $(this).serialize(); 
                            $.ajax({
                                type: 'POST',
                                url: 'search_results.php',
                                data: formData,
                                success: function(response) {
                                 
                                    $('#searchResults').html(response);
                                },
                                error: function() {
                                    // Handle errors
                                    $('#searchResults').html('<p>Error occurred while processing the request.</p>');
                                }
                            });
                        });
                    });
                    </script>

                

                <div id="searchResults">
                    <!-- Search results will be displayed here -->
                </div>
                    <?php
                        $dateAujourdhui = date('Y-m-d'); 

                         $sql = "
                            SELECT  t.lieu_depart, t.destination, t.places_max, t.places_prises,
                                    CONCAT(u.nom, ' ', u.prenom) AS nom_prenom,
                                    CONCAT(p.voiture_marque, ' ', p.voiture_modele) AS marque_modele,
                                    t.date,  t.heure_dep, t.prix
                           FROM 
                               trajet t
                           INNER JOIN 
                               pilote p ON t.pilote_user_id = p.pilote_user_id
                           INNER JOIN 
                               user u ON p.pilote_user_id = u.id
                            WHERE date >= '$dateAujourdhui'";

                        
                        $stmt = $bdd->query($sql);
                        
                        if ($stmt->rowCount() > 0) {
                            echo " <h3 id='hd2'>Trajets disponibles</h3>";

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { //displaying query result
                        ?>
                            <div class='journey-container'>
                            <div class="journey-item">                            
                                <i class="fa-solid fa-location-pin tp"></i>
                                <div class='line'>
                                    <div class="journey-info">
                                        <div class="info-section">
                                            <span class="info-label"></span>
                                            <span class="info-value"><?php echo trim(explode(",", $row["lieu_depart"])[0]);?></span>
                                        </div>
                                        <div class="info-section indexprice ">
                                            <span class="info-label"><i class="fa-solid fa-dollar-sign "></i></span>
                                            <span class="info-value prix"><?php echo $row["prix"]; ?></span>
                                        </div>
                                    </div>
                                
                                    <div class="journey-info">
                                        <div class="info-section">
                                            <span class="info-label"></span>
                                            <span class="info-value"><?php echo trim(explode(",", $row["destination"])[0]); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <i class="fa-solid fa-location-pin btm"></i>
                                
                                <div class="divider"></div> 
                                    <img width="70px" style=" border-radius: 50px; margin-left:5px; margin-top:-10px; position:absolute;" src="templates/image/driver.jpg" alt="">
                                    
                                    <div class="pilote-info">            
                                        <span class="pilote-name"><?php echo $row["nom_prenom"]; ?></span> <br>
                                        <span class="car-value"><?php echo $row["marque_modele"]; ?></span>
                                    </div>
                                <input type="button" class="resbutton" value="Reserver" onclick=redirectToLogin()>
                                                                   
                                

                                <script>
                                    function redirectToLogin() {
                                        window.location.href = 'membre/connexion.php';
                                    }

                                </script>

                            </div> <!-- journey items -->
                        </div> <!-- journey container -->
                        
                                <?php
                                
                            }
                        } 
                        ?>
                
                
            </div> <!--  quick search form -->
        </div> <!--  containerbody-->
      
    
    <!-- script references -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="gabarit/js/bootstrap.min.js"></script>
    <script src="gabarit/js/scripts.js"></script>
    
</body>
</html>