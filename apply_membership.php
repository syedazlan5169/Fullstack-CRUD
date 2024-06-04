<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id'];
    $mykad = $_POST['mykad'];

    $stmt = $conn->prepare("INSERT INTO memberships (userID, mykad) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $mykad);
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
