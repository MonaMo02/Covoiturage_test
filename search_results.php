<?php

require './config/BDD.php';
$bdd=  getBdd();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ville_depart = $_POST['ville_depart'];
    $ville_arrivee = $_POST['ville_arrivee'];
    $date = $_POST['date'];
    $nbseat = $_POST['nbseat'];

    // Prepare a SQL statement to search for matching records in the 'trajet' table
    $sql = "
        SELECT t.lieu_depart, t.destination, t.places_max, t.places_prises,
                CONCAT(u.nom, ' ', u.prenom) AS nom_prenom,
                CONCAT(p.voiture_marque, ' ', p.voiture_modele) AS marque_modele,
                t.date,  t.heure_dep, t.prix
                FROM 
                    trajet t
                INNER JOIN 
                    pilote p ON t.pilote_user_id = p.pilote_user_id
                INNER JOIN 
                    user u ON p.pilote_user_id = u.id
                WHERE lieu_depart = ? AND destination = ? AND date = ? AND places_max >= ?";
    $stmt = $bdd->prepare($sql);

    // Bind parameters and execute the query
    $stmt->execute([$ville_depart, $ville_arrivee, $date, $nbseat]);

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the fetched results in HTML format
    if ($stmt->rowCount() > 0) {
   
        echo " <div class='journey-container'>";
        echo " <h3 id='hd2'>Resultat de recherche</h3>";
        foreach ($results as $row) {
            ?>
            <div class="journey-item">                            
                <i class="fa-solid fa-location-pin tp"></i>
                <div class='line'>
                    <div class="journey-info">
                        <div class="info-section">
                            <span class="info-label"></span>
                            <span class="info-value"><?php echo $row["lieu_depart"]; ?></span>
                        </div>
                        <div class="info-section">
                            <span class="info-label"><i class="fa-solid fa-dollar-sign"></i></span>
                            <span class="info-value prix"><?php echo $row["prix"]; ?></span>
                        </div>
                    </div>
                
                    <div class="journey-info">
                        <div class="info-section">
                            <span class="info-label"></span>
                            <span class="info-value"><?php echo $row["lieu_arrivee"]; ?></span>
                        </div>
                        <!-- Add other details here -->
                    </div>
                </div>

                <i class="fa-solid fa-location-pin btm"></i>
                
                <div class="divider"></div> 
                    <img width="60px" style=" border-radius: 50px; margin-left:10px; margin-top:-5px; position:absolute;" src=" <?php echo str_replace("../", "./", $row["photo"]) ?>" alt="">
                    
                    <div class="pilote-info">            
                        <span class="pilote-name"><?php echo $row["nom_prenom"]; ?></span> <br>
                        <span class="car-value"><?php echo $row["marque_modele"]; ?></span>
                    </div>
                
                    <input type="button" class="resbutton" value="Reserver" onclick=redirectToLogin()>
                </div>
               <script>
                    function redirectToLogin() {
                        window.location.href = 'membre/connexion.php'; // Replace 'login.php' with your login page URL
                    }

                </script>
                <?php
            }

        
        }else { 
            // echo "<div  marging-top:80px;' > ";
            // echo "<img src='templates\image\undraw_windy_day_x63l.png' width='220px' style='margin-left:350px; padding-top:20px;'>";
            // echo " <h3 style='margin-left:370px; font-size:20px; color:#14525c;'> Pas encore de trajet</h3>"; 
            // echo "</div>";
        }}
    ?>




