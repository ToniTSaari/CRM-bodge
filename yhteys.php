<?php
    $servername = "localhost";
    $username = "mm19";
    $password = "mm19";
    $db = "crm";
            
    try
    {
        $yhteys = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $username, $password);
        $yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Yhteyttä ei muodostettu: " . $e->getMessage();
    }
?>