<?php
include 'dbcon.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $_POST['username_or_email'];
    $password_or_email = $_POST['password'];

    if (empty($username_or_email) || empty($password)) {
        echo "Both fields are requred";
        exit;
    }

    $sql = "SELECT id, username, email , password, authority_level FROM users WHERE username= ? OR email = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username_or_email, $password);

        if ($stmt->execute() === TRUE) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $email, $hashed_password, $authority_level);

                if ($stmt->fetch()) {
                    //Verify the passowrd
                    if (password_verify($password, $hashed_password)) {
                        //If password is corrent, start a new session and save the user's info
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['authority_level'] = $authority_level;

                        header("Locations: index.php");
                        exit;
                    } else {
                        echo "Invalid password";
                    }
                }
            } else {
                echo "No account found with that username or email";
            }
        } else {
            echo "Error: Could not execute the query: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error: Could not prepare the query: " . $conn->error;
    }
    $conn->close();

} else{
    echo "Invalid request";
}
