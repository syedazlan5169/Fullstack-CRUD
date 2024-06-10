<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_SESSION['id'];

    $stmt = $conn->prepare("UPDATE users_profile SET application_status = 'Pending' WHERE userID = ?");
    $stmt->bind_param("i",$userID);
    $stmt->execute();

    if ($stmt->execute()) {
        $_SESSION['message'] = "Your application has been submitted.";
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['message'] = "Error: Form submission is failed." . $conn->error;
        header('Location: dashboard.php');
        exit;
    }
}
$stmt->close();
$conn->close();
?>
