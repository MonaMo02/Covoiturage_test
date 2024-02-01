<?php
require'../config/formulaire.php';

require'../config/BDD.php';
$bdd = getBdd();


function print_request($bd, $request, $att_req = TRUE) {
    $reponse = $bd->query($request);

    if ($reponse === FALSE) {
        echo "Impossible d'exécuter la requête";
        return FALSE;
    } else {
        $tab_res = $reponse->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;

        if ($att_req) {
            echo '<ul class="responsive-table">';

            foreach ($tab_res as $unres) {
                if ($i == 1) {
                    echo '<li class="table-header">';
                    foreach (array_keys($unres) as $key) {
                        echo '<div class="col">' . $key . '</div>';
                    }
                    echo '</li>';
                }

                echo '<li class="table-row">';
                foreach ($unres as $value) {
                    echo '<div class="col">' . $value . '</div>';
                }
                echo '</li>';

                $i++;
            }

            echo '</ul>';
            return TRUE;
        }
    }
}



function getTablesList($bdd) {
    $tablesQuery = "SHOW TABLES;";
    $tablesResult = $bdd->query($tablesQuery);
    return $tablesResult->fetchAll(PDO::FETCH_COLUMN);
}

function getTableContent($bdd, $tableName) {
    $contentQuery = "SELECT * FROM $tableName;";
    $contentResult = $bdd->query($contentQuery);
    $contentHtml = "<div id = '$tableName'><h3>Content of Table '$tableName':</h3></div>";
    if ($contentResult) {
        $content = $contentResult->fetchAll(PDO::FETCH_ASSOC);
        $contentHtml .= "<table>";
        foreach ($content as $row) {
            $contentHtml .= "<tr><td>" . implode('</td><td>', $row) . "</td></tr>";
        }
        $contentHtml .= "</table>";
    } else {
        $contentHtml .= "Failed to fetch table content";
    }
    return $contentHtml;
}
// Add this function in librairies_admin.php
function handleAlterRequest($bdd) {
    //echo "Reached handleAlterRequest";
    // Retrieve form data
    $tableName = $_POST['TabName'];
    $command = $_POST['Command'];
    $type = $_POST['Type'];
    $name = $_POST['Name'];
    $definition = $_POST['Definition'];

    // Build your SQL query based on form data
    $sqlQuery = "ALTER TABLE $tableName ";

    if ($command == 'Add') {
        $sqlQuery .= " ADD ";
        if($type == "Column"){
            $sqlQuery .= " $name $definition ";
        } else {$sqlQuery .= "Constraint $definition ";}
        // Add logic for other attributes (name, type, definition, etc.)
    } elseif ($command == 'Drop') {
        $sqlQuery .= " DROP Column $name";
        
        // Add logic for other attributes (name, type, etc.)
    } elseif ($command == 'Modify') {
        $sqlQuery .= "MODIFY $name $definition ";
        // Add logic for other attributes (name, type, definition, etc.)
    }
    //echo $sqlQuery;
    $bdd->query($sqlQuery);

    // Redirect or display a success message
    // header('Location: librairies_admin.php');
    // exit;
}


function AlterRequestForm() {
    ob_start(); //on met en memoire tampon le code hmtl qui va suivre

    echo "<h1 class='logonavbar' style='margin-left: 25px;'>Alter request form</h1>";
    echo "<form name='form' id='alterformrequest' method='POST' action='librairies_admin.php?action=alterFormContainer'>";
    //form_debut("form id="alterformrequest"", "POST", "librairies_admin.php");
    form_input_text("TabName", TRUE, "Table name", "", 30, "");
    echo"<br><br>";
    form_select("Command", false , 0  ,array("Add","Drop","Modify"));
    echo"<br><br>";
    form_select("Type",false, 0,  array("Column", "Constraint"));
    echo"<br><br>";
    form_input_text("Name", TRUE, "Name", "", 30, "");
    echo"<br><br>";
    form_input_text("Definition", TRUE, "Definition", "", 30, "");
    echo"<br><br>";
    form_submit("submit","submit", FALSE);
    form_reset("Reset", "Reset", FALSE, FALSE);
    form_fin();

    return ob_get_clean();
}


function getCount($bdd, $tableName) {
    $query = "SELECT COUNT(*) as count FROM $tableName";
    $result = $bdd->query($query);
    
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    } else {
        // Handle the error or return an appropriate value
        return 0;
    }
}
?>