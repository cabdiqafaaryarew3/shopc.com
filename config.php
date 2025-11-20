<?php
$host = "localhost";
$user = "root"; // default for XAMPP
$pass = ""; // leave empty if no password
$dbname = "shopc_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
