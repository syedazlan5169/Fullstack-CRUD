<?php
$servername = "139.59.218.52";
$username = "lan-sql-ext";
$password = "Lanpke050890!";
$dbname = "FullstackCRUD_DB";

$conn = new mysqli($servername, $username, $password,$dbname);

if ($conn->connect_error) {
    die("Connection failed" . $conn->connect_error);
}
?>
