<?php
require 'functions.php'; // Adjust the path if necessary
require '../config/droits.php';
test_admin();


ob_start();

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'showTablesAjax') {
//     $tables = getTablesList($bdd);
//     $tableHtml = "<h2>Tables in the Database:</h2>";
//     foreach ($tables as $table) {
//         $tableHtml .= "<button class='tableToggle' data-table='$table'>$table</button>
//                         <div class='tableContent' id='tableContent_$table'></div>";
//     }
//     echo $tableHtml;
//     exit; // Stop further execution after sending the AJAX response
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'getTableContent') {
//     $tableName = $_POST['tableName'];
    
//     // Add your logic to fetch and display the content of the specified table
//     $tableContent = getTableContent($bdd, $tableName);

//     echo $tableContent;
//     exit; // Stop further execution after sending the AJAX response
// }


echo "
<div class='col-md-4' id='rightCol'>";
                
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
    </script>";

    if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
        // Retrieve form data
        handleAlterRequest($bdd);
        // Redirect or display a success message
        // header('Location: librairies_admin.php');
        // exit;
    }

    $form = AlterRequestForm(); 
    echo"
    
    $form


    </div>";





echo"
<div class='col-md-8' id='leftCol'>";
echo "<h1>Comptes Utilisateurs</h1>";
print_request($bdd, "SELECT id, nom, prenom, login, email FROM user;"); //on appelle simplement la fonction pour obtenir toutes les informations sur les comptes
echo"

<h1>Trajets restant a effectuer</h1>";
$reponse = $bdd->query("SELECT id, effectue, lieu_depart, destination, date, heure_dep FROM trajet"); //on stocke les informations des trajets
$reponse_nb_trajet = $bdd->query("SELECT count(*) FROM trajet");
echo"<table><tr><th>ID</th><th>Ville de depart</th><th>Ville d'arrivee</th><th>Date</th><th>Heure</th><th>Pilote<br>Nom  Prenom</th><th>Passagers<br>Nom  Prenom</th></tr>"; //on crée le tableau
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
    if ($tab_res["effectue"] == 0) { //si le trajet n'est pas effectué (effetue = 0) on l'affiche
        echo"<tr><td>" . $tab_res["id"] . "</td><td>" . $tab_res["lieu_depart"] . "</td><td>" . $tab_res["destination"] . "</td><td>" . $tab_res["date"] . "</td><td>" . $tab_res["heure_dep"] . "</td>";
        echo"<td><table>";
        while ($pilote = $reponse_pilote->fetch()) {
            echo"<td><a href='../membre/profil.php?login=".$pilote["login"]."'>" . $pilote["nom"] . "</a></td><td>" . $pilote["prenom"] . "</td>";
        }
        echo"</td></table>";
        echo"<td><table>";
        while ($tab_passagers = $reponse_passagers->fetch()) {
            echo"<tr><td><a href='../membre/profil.php?username=".$tab_passagers["username"]."'>" . $tab_passagers["nom"] . "</a></td><td>" . $tab_passagers["prenom"] . "</td></tr>";
        }
        echo"</table></td>";
    }else{
        
    }
    $index_id = $index_id + 1;
}

echo "</div>";


$contenu=ob_get_clean(); 

ob_clean();

$countusers = getCount($bdd, 'user');

echo"
<div class='col-md-4'> USERS : $countusers</div>
";

$countrides = getCount($bdd, 'trajet');
echo"
<div class='col-md-4'> RIDES : $countrides </div>
";

$countdrivers = getCount($bdd, 'pilote');
echo"
<div class='col-md-4'> Drivers : $countdrivers </div>
";

$contenu2 = ob_get_clean();

// 
// $countdrivers = getCount($bdd, 'pilote');


$title = "Administration";
require '../templates/pages/gabarit_admin.php';

?>