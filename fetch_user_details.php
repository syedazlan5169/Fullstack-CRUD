<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include 'dbcon.php';

if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    $query = "SELECT * FROM users_profile WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userDetails = $result->fetch_assoc();
        echo json_encode($userDetails);
    } else {
        echo json_encode([]);
    }

    $stmt->close();
}

$conn->close();
