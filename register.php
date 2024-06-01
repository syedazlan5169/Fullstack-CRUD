<?php
use MongoDB\BSON\Type;
use UI\Draw\Text\Font\Descriptor;
use UI\Window;

include 'dbcon.php';

//Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //Validate the form data
    if (empty($username) || empty($password) || empty($email)) {
        echo 'All fields are required';
        exit;
    }

    //Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">
                    alert("Registration successful, click ok to redirect to login page.");
                    window.location.href = "login_form.php";
                  </script>';
            exit;
        } else {
            echo "Error: Could not execute the query: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error: Could not prepare the query: " . $conn->error;
    }
    $conn->close();
}
else {
    echo "Invalid request.";
}