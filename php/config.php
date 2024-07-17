<?php
$servername = "tameszaza.mysql.pythonanywhere-services.com";  // Replace with your server's host name or IP address
$username = "tameszaza";       // Replace with your MySQL username
$password = "adulvitch";       // Replace with your MySQL password
$dbname = "tameszaza$LogIn";               // Replace with your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// If connection is successful
echo "Connected successfully";

?>
