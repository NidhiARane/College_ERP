<?php
$host = "localhost";
$dbname = "college_erp";
$username = "root";
$password = "";

try
{
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password

    );
    
    $conn->setAttribute (
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );    
}
catch(PDOException $ex)
{
    die("database connection failed-".$ex->getMessage()

    );
}
?>