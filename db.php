<?php
$host = "sql100.infinityfree.com";      
$user = "if0_40508624";                  
$pass = "ITOge264Ri";                    
$db   = "if0_40508624_stylemart";       

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
