<?php

$searchVal = strtolower($_POST['searchVal']);
$format = $_POST['format'];
$sector = $_POST['sector'];

$host='localhost';
$db = SENG401; //use pgadmin to create a database e.g. SENG401
$username = 'Ross'; //usually postgres
$password = 'postgres'; //usually postgres
$port = 5432;
$dsn = "pgsql:host=$host; port=$port; dbname=$db; user=$username;password=$password";


try{
    // create a PostgreSQL database connection
    $conn = new PDO($dsn);
    if($conn){
        //echo "Connected to the <strong>$db</strong> database successfully!<hr>";
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $queryStatement = "SELECT * FROM CalgarySchools WHERE lower(name) LIKE '%$searchVal%'";
        if($sector != 'Any') $queryStatement .= " AND sector = '" .$sector. "'";

        $query = $conn->query($queryStatement);
        $results = $query->fetchAll();

        if(sizeof($results)  == 0) {
            echo "No results."; 
            die();
        }
        else echo "Your search matched <strong>". sizeof($results) ."</strong> result(s).<br><br>";

        //group/count by school type
        outputTypeCountTable($conn);
        echo "<hr>";
        
        //output search results 
        if($format === 'table') outputTable($results);
        else if($format === 'CSV') outputCSV($results);
        else if($format === 'JSON') outputJSON($results);
        else if($format === 'XML') outputXML($results);
    }
}
catch (PDOException $e){
    // report error message
    echo $e->getMessage();
 }


function outputTable($results){
    echo '<table>';
    //make header row
    echo "<tr><th></th><th>Name</th><th>Type</th><th>Sector</th><th>Address</th><th>Postal Code</th></tr>";
    $count = 0;
    foreach($results as $result){
        $count++;
        echo '<tr>';
        echo "<td>$count.</td>";
        echo "<td>".$result['name']."</td>";
        echo "<td>".$result['type']."</td>";
        echo "<td>".$result['sector']."</td>";
        echo "<td>".$result['address']."</td>";
        echo "<td>".$result['postalcode']."</td>";
        echo '</tr>';
    }
    echo '</table>';
}

function outputCSV($results){
    foreach($results as $result){
        echo $result['name'] . ", " . $result['type']. ", " . $result['sector'] . ", ". $result['address'] . ", " . $result['postalcode']. "<br>";
    }
}

function outputJSON($results){
    $json = json_encode($results, JSON_PRETTY_PRINT);
    echo "<pre>" . print_r($json, true) . "</pre>";
}

function outputXML($results){
    //creating object of SimpleXMLElement
    $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><results></results>");
    //function call to convert array to xml
    array_to_xml($results,$xml);
    //TODO: can't figure out how to make this pretty print
    echo $xml->asXML();
}

 //function defination to convert array to xml
function array_to_xml($array, &$xml_user_info) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_user_info->addChild("$key");
                array_to_xml($value, $subnode);
            }else{
                $subnode = $xml_user_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }else {
            $xml_user_info->addChild("$key",htmlspecialchars("$value"));
        }
    }
}


function outputTypeCountTable($conn){
    global $sector, $searchVal;
    //make statement
    $queryStatement = "SELECT type, COUNT(*) AS total FROM CalgarySchools WHERE lower(name) LIKE '%$searchVal%'";
    if($sector != 'Any') $queryStatement .= " AND sector = '" .$sector. "'";
    $queryStatement .= " GROUP BY type ORDER BY total DESC";
    //send query
    $query = $conn->query($queryStatement);
    $results = $query->fetchAll();
    //generate table
    echo '<table>';
    echo "<tr>";
    echo "<th>Type</th>";
    echo "<th>Total</th>";
    echo "</tr>";
    foreach($results as $r){
        echo '<tr>';
        echo "<td>".$r['type']."</td>";
        echo "<td>".$r['total']."</td>";
        echo '</tr>';
    }
    echo '</table>';
}
?>