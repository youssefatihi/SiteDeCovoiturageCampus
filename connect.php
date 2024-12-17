<?php
$servername = "localhost";
$username = "root"; // default username for localhost
$password = ""; // default password for localhost is usually empty
$dbname = "covoiturage_db"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
