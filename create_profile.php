<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include 'dbcon.php';

$userID = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mykad = $_POST['mykad'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];
    $sex = $_POST['sex'];
    $state = $_POST['state'];

    $query = "INSERT INTO users_profile (userID, mykad, name, phone, birthdate, sex, state) VALUES ('$userID', '$mykad', '$name', '$phone', '$birthdate', '$sex', '$state')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Profile created successfully.";
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['message'] = "Error: Could not create profile. " . $conn->error;
        header('Location: dashboard.php');
    }
} ?> 