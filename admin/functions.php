<?php
require'../config/formulaire.php';

require'../config/BDD.php';
$bdd = getBdd();


function print_request($bd, $request, $att_req = TRUE) {   //fonction permettant d'afficher la requete sql dans un tableau dynamique
    $reponse = $bd->query($request); //on stocke le resultat de la requete
    if ($reponse === FALSE) { //si la requete n'a pas aboutit
        echo"impossible d'executer la requete";
        return FALSE;
    } else {
        $tab_res = $reponse->fetchALL(PDO::FETCH_ASSOC); //sinon on parcours chaque element de la requete, en plaçant les clés dans un <th> et les valeurs dans les lignes suivantes
        $i = 1;
        if ($att_req) {
            //echo $request; //enlever le commentaire pour afficher la requete
            echo "<table>";
            foreach ($tab_res as $unres) {
                if ($i == 1) {
                    echo"<tr><th>" . implode('</th><th>', array_keys($unres)) . "</th></tr>";
                    $i++;
                }
                echo"<tr><td>" . implode('</td><td>', $unres) . "</td></tr>";
            }
            echo"</table>";
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

    echo "<h1>Alter request form</h1>";
    echo "<form name='form' id='alterformrequest' method='POST' action='librairies_admin.php?action=alterFormContainer'>";
    //form_debut("form id="alterformrequest"", "POST", "librairies_admin.php");
    form_label("Table name");
    form_input_text("TabName", TRUE, "", "", 30, "");
    echo"<br><br>";
    form_label("Command");
    form_checkbox("Command", array("Add","Drop","Modify"), array(FALSE,FALSE,FALSE), array(FALSE,FALSE ,FALSE) , array("","",""));
    echo"<br><br>";
    form_label("Type");
    form_checkbox("Type", array("Column", "Constraint"),array( FALSE, FALSE) ,array(FALSE ,FALSE ), array("", ""));
    echo"<br><br>";
    form_label("Name");
    form_input_text("Name", TRUE, "", "", 30, "");
    echo"<br><br>";
    form_label("Definition");
    form_input_text("Definition", TRUE, "", "", 30, "");
    echo"<br><br>";
    form_submit("submit","submit", FALSE);
    form_reset("Reset", "Reset", FALSE, FALSE);
    form_fin();

    return ob_get_clean();
}

?>