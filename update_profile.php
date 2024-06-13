<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['userID'] == NULL) {
        $userID = $_SESSION['id'];
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
    } else {
        $userID = $_POST['userID'];
        $mykad = $_POST['mykadDetail'];
        $name = $_POST['nameDetail'];
        $phone = $_POST['phoneDetail'];
        $birthdate = $_POST['birthdateDetail'];
        $sex = $_POST['sexDetail'];
        $membership_number = $_POST['membershipNumberDetail'];
        $member_since = $_POST['memberSinceDetail'];
        $application_status = $_POST['applicationStatusDetail'];
        $comment = $_POST['commentDetail'];

        $query = "UPDATE users_profile SET mykad='$mykad', name='$name', phone='$phone', birthdate='$birthdate', sex='$sex', membership_number='$membership_number', application_status='$application_status', comment='$comment' WHERE userID='$userID'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = $name . "'s profile updated successfully.";
            header('Location: dashboard.php');
            exit;
        } else {
            $_SESSION['message'] = "Error: Could not update profile." . $conn->error;
            header('Location: dashboard.php');
            exit;
        }
    }
}

