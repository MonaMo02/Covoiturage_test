<?php
require 'functions.php'; // Adjust the path if necessary
require '../config/droits.php';
test_admin();


ob_start();

echo '
<div id="rightCol" style = "width : 30%;"> ';

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
    // Retrieve form data
    handleAlterRequest($bdd);
    // Redirect or display a success message
    // header('Location: librairies_admin.php');
    // exit;
}

$form = AlterRequestForm(); 
echo" <div class = 'alterform' >  $form  </div>";

                
$rideDataQuery = "SELECT lieu_depart, COUNT(*) AS ride_count FROM trajet GROUP BY lieu_depart;";
$rideDataResult = $bdd->query($rideDataQuery);

if ($rideDataResult) {
    $rideChartData = $rideDataResult->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Failed to fetch ride data: " . print_r($bdd->errorInfo(), true));
}

$labels = json_encode(array_map('strval', array_column($rideChartData, 'lieu_depart')));
$data = json_encode(array_map('strval', array_column($rideChartData, 'ride_count')));

echo "<canvas id='ridesPerCityChart' width = 100px height = 100px></canvas>
    <script>
        var ctx = document.getElementById('ridesPerCityChart').getContext('2d');
        var labels = " . $labels . ";
        var data = " . $data . ";
        var ridesPerCityChart = new Chart(ctx, {
            type: 'pie', // Change chart type to 'pie'
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Rides',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                // If you need any specific options for the pie chart, add them here
            }
        });
    </script>
    </div>";





echo'
<div  id="leftCol" style = "width : 60%; margin-left: 10%;">';
echo "<h1 class='logonavbar' style ='margin-left: 25%'>Comptes Utilisateurs</h1>";
print_request($bdd, "SELECT id, nom, prenom, login, email FROM user ORDER BY RAND() LIMIT 10;"); //on appelle simplement la fonction pour obtenir toutes les informations sur les comptes

echo "</div>";


$contenu=ob_get_clean(); 

ob_start();

echo "
<h1 class='logonavbar'>Trajets restant à effectuer</h1>
<ul class='responsive-table'>
  <li class='table-header'>
    <div class='col col-1'>ID</div>
    <div class='col col-2'>Ville de départ</div>
    <div class='col col-3'>Ville d'arrivée</div>
    <div class='col col-4'>Date</div>
    <div class='col col-5'>Heure</div>
    <div class='col col-6'>Pilote</div>
  </li>";

$reponse = $bdd->query("SELECT id, effectue, lieu_depart, destination, date, heure_dep FROM trajet where effectue = 0");
$reponse_nb_trajet = $bdd->query("SELECT count(*) FROM trajet");
$nb_trajet = $reponse_nb_trajet->fetch();
$index_id = 1;

while ($tab_res = $reponse->fetch()) {
    $reponse_passagers = $bdd->query("SELECT nom, prenom, login FROM trajet_passager TP, user U, trajet T "
        . "WHERE TP.user_id = U.id "
        . "AND TP.trajet_id = T.id "
        . "AND TP.trajet_id = "
        . $index_id . " "
        . "AND effectue = 0 "
        . "GROUP BY TP.user_id;");

    $reponse_pilote = $bdd->query("SELECT nom, prenom, login FROM trajet T, user U "
        . "WHERE T.pilote_user_id = U.id "
        . "AND T.id = "
        . $index_id . " "
        . "AND effectue = 0 "
        . "GROUP BY T.id");

    if ($tab_res["effectue"] == 0) {
        echo "<li class='table-row'>";
        echo "<div class='col col-1'>" . $tab_res["id"] . "</div>";
        echo "<div class='col col-2'>" . $tab_res["lieu_depart"] . "</div>";
        echo "<div class='col col-3'>" . $tab_res["destination"] . "</div>";
        echo "<div class='col col-4'>" . $tab_res["date"] . "</div>";
        echo "<div class='col col-5'>" . $tab_res["heure_dep"] . "</div>";

        echo "<div class='col col-6'><table>";
        while ($pilote = $reponse_pilote->fetch()) {
            echo "<tr><td><a href='../membre/profil.php?login=" . $pilote["login"] . "'>" . $pilote["nom"] . "</a></td><td>" . $pilote["prenom"] . "</td></tr>";
        }
        echo "</table></div>";

        

        echo "</li>";
    } else {
        // Handle the case when the trip is completed
    }

    $index_id = $index_id + 1;
}

echo "</ul>";

$contenu3 = ob_get_clean();

ob_start();

$countusers = getCount($bdd, 'user');
$countdrivers = getCount($bdd, 'pilote');
$countrides = getCount($bdd, 'trajet');

echo"
<div class='col-md-3 custom-card'> <h4 class = 'logonavbar' > USERS : $countusers</h4> </div>
<div class='col-md-3 custom-card'> <h4 class = 'logonavbar' > RIDES : $countrides</h4>  </div>
<div class='col-md-3 custom-card'> <h4 class = 'logonavbar' > Drivers : $countdrivers</h4>  </div>
";

$contenu2 = ob_get_clean();

// 
// $countdrivers = getCount($bdd, 'pilote');


$title = "Administration";
require '../templates/pages/gabarit_admin.php';

?>