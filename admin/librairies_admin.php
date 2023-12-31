<?php
require 'functions.php'; // Adjust the path if necessary
require '../config/droits.php';
test_admin();


ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'showTablesAjax') {
    $tables = getTablesList($bdd);
    $tableHtml = "<h2>Tables in the Database:</h2>";
    foreach ($tables as $table) {
        $tableHtml .= "<button class='tableToggle' data-table='$table'>$table</button><div class='tableContent' id='tableContent_$table'></div>";
    }
    echo $tableHtml;
    exit; // Stop further execution after sending the AJAX response
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'getTableContent') {
    $tableName = $_POST['tableName'];
    
    // Add your logic to fetch and display the content of the specified table
    $tableContent = getTableContent($bdd, $tableName);

    echo $tableContent;
    exit; // Stop further execution after sending the AJAX response
}



//database interface : 
echo "<h1> DATABASE INTERFACE</h1>";

//SHOW TABLES
echo "<button id='toggleshowtabs' class='btn btn-primary'>Show tables</button>";
echo "<div id='tablesContainer' style='display:none;'></div>
<script>
document.getElementById('toggleshowtabs').addEventListener('click', function() {
    // Fetch and display tables using AJAX
    fetch('librairies_admin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=showTablesAjax',
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('tablesContainer').innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
    tablesContainer.style.display = (tablesContainer.style.display === 'none') ? 'block' : 'none';

});
document.getElementById('tablesContainer').addEventListener('click', function(event) {
    if (event.target.classList.contains('tableToggle')) {
        var tableName = event.target.getAttribute('data-table');
        var tableContentContainer = document.getElementById('tableContent_' + tableName);

        // Fetch and display table content using AJAX
        fetch('librairies_admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=getTableContent&tableName=' + tableName,
        })
        .then(response => response.text())
        .then(data => {
            tableContentContainer.innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
        tableContentContainer.style.display = (tableContentContainer.style.display === 'none') ? 'block' : 'none';
    }
});
</script>";
// END SHOW TABLES 


if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
    // Retrieve form data
    handleAlterRequest($bdd);
    // Redirect or display a success message
    // header('Location: librairies_admin.php');
    // exit;
}


//ALTER TABLE
echo "<button id='togglealtertable' class='btn btn-primary'>Alter table</button>";
echo "<div id='alterFormContainer' style = 'display : none';>";
    $form = AlterRequestForm();
    echo"$form";
echo "</div>
<script>
document.getElementById('togglealtertable').addEventListener('click', function() {
    var alterFormContainer = document.getElementById('alterFormContainer');
    alterFormContainer.style.display = (alterFormContainer.style.display === 'none') ? 'block' : 'none';
});
</script>";

//END ALTER TABLE

//DASHBOARD/GRAPHS/MAP
echo "<h1>Dashboard</h1>";


// //Fetch ride data from the database
// $rideDataQuery = "SELECT lieu_depart, COUNT(*) AS ride_count FROM trajet GROUP BY lieu_depart;";
// $rideDataResult = $bdd->query($rideDataQuery);

// if ($rideDataResult) {
//     $rideChartData = $rideDataResult->fetchAll(PDO::FETCH_ASSOC);
// } else {
//     die("Failed to fetch ride data: " . print_r($bdd->errorInfo(), true));
// }

// $labels = json_encode(array_map('strval', array_column($rideChartData, 'lieu_depart')));
// $data = json_encode(array_map('strval', array_column($rideChartData, 'ride_count')));

// echo "<canvas id='ridesPerCityChart' width='400' height='200'></canvas>

// <script>
//     var ctx = document.getElementById('ridesPerCityChart').getContext('2d');

//     // Separate the arrays for labels and data
//     var labels = " . $labels . ";
//     var data = " . $data . ";

//     var ridesPerCityChart = new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: labels,
//             datasets: [{
//                 label: 'Number of Rides',
//                 data: data,
//                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
//                 borderColor: 'rgba(75, 192, 192, 1)',
//                 borderWidth: 1
//             }]
//         },
//         options: {
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             }
//         }
//     });
// </script>";


// Assume $selectedVille is the selected "ville depart" from your input mechanism (e.g., dropdown)

// $rideDataQuery = "SELECT MONTH(date) AS month, COUNT(*) AS ride_count 
//                   FROM trajet 
//                   WHERE YEAR(date) = 2015 AND lieu_depart = :selectedVille
//                   GROUP BY MONTH(date);";
// $rideDataResult = $bdd->prepare($rideDataQuery);
// $rideDataResult->bindParam(':selectedVille', $selectedVille, PDO::PARAM_STR);
// $rideDataResult->execute();

// if ($rideDataResult) {
//     $rideChartData = $rideDataResult->fetchAll(PDO::FETCH_ASSOC);
// } else {
//     die("Failed to fetch ride data: " . print_r($bdd->errorInfo(), true));
// }

// // ... (rest of your HTML and Chart.js code)

// //Dropdown or input mechanism to choose "ville depart" -->
// // Assume $selectedVille is the selected "ville depart" from your input mechanism (e.g., dropdown)
// $selectedVille = isset($_GET['selectedVille']) ? $_GET['selectedVille'] : null;

// // Fetch distinct "ville depart" values from the table
// $distinctVillesQuery = "SELECT DISTINCT lieu_depart FROM trajet;";
// $distinctVillesResult = $bdd->query($distinctVillesQuery);

// if ($distinctVillesResult) {
//     $distinctVilles = $distinctVillesResult->fetchAll(PDO::FETCH_COLUMN);
// } else {
//     die("Failed to fetch distinct ville data: " . print_r($bdd->errorInfo(), true));
// }

// // ... (rest of your HTML)

// // Dropdown options based on the fetched "ville depart" values
// echo "<form method='get'>
//     <label for='selectedVille'>Select Ville:</label>
//     <select id='selectedVille' name='selectedVille'>";
// foreach ($distinctVilles as $ville) {
//     $selected = ($selectedVille === $ville) ? "selected" : "";
//     echo "<option value='$ville' $selected>$ville</option>";
// }
// echo "</select>
//     <button type='submit'>Submit</button>
// </form>";

// $labels = json_encode(array_column($rideChartData, 'month')); 
// $data =  json_encode(array_column($rideChartData, 'ride_count')); 
    

// //<!-- Your Canvas and Chart.js script -->
// echo "<canvas id='ridesPerCityChart' width='400' height='200'></canvas>
// <script>
//     // Use PHP to pass data to JavaScript
    
//     var ctx = document.getElementById('ridesPerCityChart').getContext('2d');
//     var labels =".$labels.";
//     var data = ".$data.";
//     var ridesPerCityChart = new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: labels,
//             datasets: [{
//                 label: 'Number of Rides',
//                 data: data,
//                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
//                 borderColor: 'rgba(75, 192, 192, 1)',
//                 borderWidth: 1
//             }]
//         },
//         options: {
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             }
//         }
//     });
// </script>";



// Fetch data for the pie chart
$rideDataQuery = "SELECT lieu_depart, COUNT(*) AS ride_count FROM trajet GROUP BY lieu_depart;";
$rideDataResult = $bdd->query($rideDataQuery);

if ($rideDataResult) {
    $rideChartData = $rideDataResult->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Failed to fetch ride data: " . print_r($bdd->errorInfo(), true));
}


//<!-- Your HTML code -->
$labels = json_encode(array_column($rideChartData, 'lieu_depart'));
$data = json_encode(array_column($rideChartData, 'ride_count')); 
echo "<div>
<canvas id='ridesPerCityChart' width='300' height='200'></canvas>
</div>
<script>
    var ctx = document.getElementById('ridesPerCityChart').getContext('2d');

    // Separate the arrays for labels and data
    var labels = ".$labels.";
    var data = ".$data.";

    var ridesPerCityChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right',
            }
        }
    });
</script>";



// //USERS TABLE 
// echo "<h1>Comptes Utilisateurs</h1>";

// echo "<button id='toggleUserTableBtn' class='btn btn-primary'>Toggle User Table</button>";
// echo '<div id="userTableContainer" style="display:none;">'; // Initially hide the container

// print_request($bdd,  "SELECT id, nom, prenom, username, email, birthday, compte, note FROM user;"); //on appelle simplement la fonction pour obtenir toutes les informations sur les comptes

// echo "</div>

// <script>
//     document.getElementById('toggleUserTableBtn').addEventListener('click', function () {
//         var userTableContainer = document.getElementById('userTableContainer');
//         userTableContainer.style.display = (userTableContainer.style.display === 'none') ? 'block' : 'none';
//     });
// </script>";



// echo "<h1>Trajets restant a effectuer</h1>";
// $reponse = $bdd->query("SELECT id, effectue, lieu_depart, lieu_arrivee, date, heure_dep FROM trajet"); //on stocke les informations des trajets
// $reponse_nb_trajet = $bdd->query("SELECT count(id) FROM trajet");

// echo "<button id='toggleTripsTableBtn' class='btn btn-primary'>Toggle Remaining Trips Table</button>";
// echo "<div id='tripsTableContainer' style='display:none;'>"; // Initially hide the container

// echo"<table><tr><th>ID</th><th>Ville de depart</th><th>Ville d'arrivee</th><th>Date</th><th>Heure</th><th>Pilote<br>Nom  Prenom</th><th>Passagers<br>Nom  Prenom</th></tr>"; //on crée le tableau
// $nb_trajet = $reponse_nb_trajet->fetch();
// $index_id = 1;
// while ($tab_res = $reponse->fetch()) {
//     $reponse_passagers = $bdd->query("SELECT nom, prenom, username FROM trajet_passager TP, user U, trajet T "
//             . "WHERE TP.user_id = U.id "
//             . "AND TP.trajet_id = T.id "
//             . "AND TP.trajet_id = "
//             . $index_id . " "
//             . "AND effectue = 0 "
//             . "GROUP BY TP.user_id;");
//     $reponse_pilote = $bdd->query("SELECT nom, prenom, username FROM trajet T, user U "
//             . "WHERE T.pilote_user_id = U.id "
//             . "AND T.id = "
//             . $index_id . " "
//             . "AND effectue = 0 "
//             . "GROUP BY T.id");
//     if ($tab_res["effectue"] == 0) { //si le trajet n'est pas effectué (effetue = 0) on l'affiche
//         echo"<tr><td>" . $tab_res["id"] . "</td><td>" . $tab_res["lieu_depart"] . "</td><td>" . $tab_res["lieu_arrivee"] . "</td><td>" . $tab_res["date"] . "</td><td>" . $tab_res["heure_dep"] . "</td>";
//         echo"<td><table>";
//         while ($pilote = $reponse_pilote->fetch()) {
//             echo"<td><a href='../membre/profil.php?username=".$pilote["username"]."'>" . $pilote["nom"] . "</a></td><td>" . $pilote["prenom"] . "</td>";
//         }
//         echo"</td></table>";
//         echo"<td><table>";
//         while ($tab_passagers = $reponse_passagers->fetch()) {
//             echo"<tr><td><a href='../membre/profil.php?username=".$tab_passagers["username"]."'>" . $tab_passagers["nom"] . "</a></td><td>" . $tab_passagers["prenom"] . "</td></tr>";
//         }
//         echo"</table></td>";
//     }else{
        
//     }
//     $index_id = $index_id + 1;
// }

// echo "</div>
// <script>
//     document.getElementById('toggleTripsTableBtn').addEventListener('click', function () {
//         var tripsTableContainer = document.getElementById('tripsTableContainer');
//         tripsTableContainer.style.display = (tripsTableContainer.style.display === 'none') ? 'block' : 'none';
//     });
// </script>";

$contenu=ob_get_clean();   


   
$title = "Administration";
require '../templates/pages/gabarit_admin.php';

?>