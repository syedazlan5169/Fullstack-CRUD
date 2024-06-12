<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include 'dbcon.php';

$searchResults = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mykad = $_POST['mykad'];
    $name = $_POST['name'];
    $membership_number = $_POST['membership_number'];
    $application_status = $_POST['application_status'];

    $query = "SELECT * FROM users_profile WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($mykad)) {
        $query .= " AND mykad = ?";
        $params[] = $mykad;
        $types .= "s";
    }
    if (!empty($name)) {
        $query .= " AND name LIKE ?";
        $params[] = "%$name%";
        $types .= "s";
    }
    if (!empty($membership_number)) {
        $query .= " AND membership_number = ?";
        $params[] = $membership_number;
        $types .= "s";
    }
    if (!empty($application_status)) {
        $query .= " AND application_status = ?";
        $params[] = $application_status;
        $types .= "s";
    }

    $stmt = $conn->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $statusClass = '';
            if ($row['application_status'] == 'Approved') {
                $statusClass = 'approved';
            } elseif ($row['application_status'] == 'Pending') {
                $statusClass = 'pending';
            } elseif ($row['application_status'] == 'Rejected') {
                $statusClass = 'rejected';
            }

            echo '<tr data-userid="' . htmlspecialchars($row['userID']) . '">';
            echo '<td>' . htmlspecialchars($row['mykad']) . '</td>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['sex']) . '</td>';
            echo '<td class="' . $statusClass . '">' . htmlspecialchars($row['application_status']) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10">No results found.</td></tr>';
    }

    $stmt->close();
}

$conn->close();
