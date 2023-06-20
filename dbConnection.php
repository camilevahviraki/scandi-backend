<?php
$servername = "localhost";
$username = "camile";
$password = "root";
$database = "scandi-junior-test-db";

$conn = new mysqli($servername, $username, $password);

$create_db = "CREATE DATABASE IF NOT EXISTS `" . $database . "`";

$table_product = "CREATE TABLE IF NOT EXISTS products (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  sku VARCHAR(40) NOT NULL,
  price INTEGER NOT NULL,
  attributeName VARCHAR(30) NOT NULL, 
  attributeValue VARCHAR(30) NOT NULL,
  sizeMB VARCHAR(30),
  weightKG VARCHAR(30),
  dimentions VARCHAR(30)
)";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($conn->query($create_db) === true) {
    
    $conn->select_db($database);

    if($conn->query($table_product) === false){
        echo "Error creating tables!";
    }else {
        // echo "Tables created and/or connected successfully!";
    }
    
} else {
    echo "Error creating database: " . $conn->error;
}

?>