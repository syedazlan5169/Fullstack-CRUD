<?php
$servername = "139.59.218.52";
$username = "lan-sql-ext";
$password = "Lanpke050890!";
$dbname = "FullstackCRUD_DB";

$conn = new mysqli($servername, $username, $password,$dbname);

if ($conn->connect_error) {
    die("Connection failed" . $conn->connect_error);
}
/*$sql = "INSERT INTO users (username, password) VALUES ('syedazlan5169', 'Lanpke050890')";
try {
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        throw new Exception("Error: " . $sql . "<br>" . $conn->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
$conn->close();*/
?>
