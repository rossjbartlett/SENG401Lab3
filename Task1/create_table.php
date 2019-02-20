<?php

//NOTE: should only need to run this file once 

$host='localhost';
$db = SENG401; //use pgadmin to create a database e.g. SENG401
$username = 'Ross'; //usually postgres
$password = 'postgres'; //usually postgres
$port = 5432;
$dsn = "pgsql:host=$host; port=$port; dbname=$db; user=$username;password=$password";

try{
    // create a PostgreSQL database connection
    $conn = new PDO($dsn);
    // display a message if connected to the PostgreSQL successfully
    if($conn){
        echo "Connected to the <strong>$db</strong> database successfully!<br>";
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //if the table exists already, delete it
        $stmt=$conn->prepare("DROP TABLE IF EXISTS CalgarySchools;");
        if($stmt->execute()){
            echo "Existing table deleted!<br>";
        }
        
        //create the table
        $sql = "CREATE TABLE CalgarySchools (name varchar(128), type varchar(64), sector
                varchar(2), address varchar(256), city varchar(16), province varchar(16),
                postalcode varchar(7), longitude double precision, latitude double precision);";
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
            echo "Table created!<br>";
        }

        //copy CSV file into table
        $path = '/Users/Ross/Sites/SENG401/Lab3/CalgarySchools.csv'; // relative path did not work?
        $sql = "COPY CalgarySchools FROM $path WITH DELIMITER ',' CSV HEADER;";
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
            echo "Contents copied from CSV!<br>";
        }


    }
}
catch (PDOException $e){
    // report error message
    echo $e->getMessage();
 }

?>