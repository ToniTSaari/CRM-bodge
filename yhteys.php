<?php
    $servername = "localhost";
    $username = " ";
    $password = " ";
    $db = " ";
            
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
