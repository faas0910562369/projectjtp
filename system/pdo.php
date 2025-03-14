<?php
$host = 'localhost';
$dbname = 'test'; 
$username = 'root'; 
$password = ''; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>