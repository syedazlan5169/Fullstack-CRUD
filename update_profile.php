<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include 'dbcon.php';

$userID = $_SESSION['id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];
    $sex = $_POST['sex'];
    $state = $_POST['state'];

    $query = "UPDATE users_profile SET name='$name', phone='$phone', birthdate='$birthdate', sex='$sex', state='$state' WHERE userID='$userID'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Profile updated successfully.";
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['message'] = "Error: Could not update profile." . $conn->error;
        header('Location: dashboard.php');
        exit;
    }
}

