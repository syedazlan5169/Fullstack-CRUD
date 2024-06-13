<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include 'dbcon.php';

$username = $_SESSION['username'];
$authority_level = $_SESSION['authority_level'];

// Fetch user data from users table
$query_user = "SELECT * FROM users WHERE username = '$username'";
$result_user = mysqli_query($conn, $query_user);
$user = mysqli_fetch_assoc($result_user);

// Fetch user profile data from users_profile table
$userID = $user['id'];
$query_profile = "SELECT * FROM users_profile WHERE userID = '$userID'";
$result_profile = mysqli_query($conn, $query_profile);
$profile_exists = mysqli_num_rows($result_profile) > 0;

if ($profile_exists) {
    $profile = mysqli_fetch_assoc($result_profile);
}

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .approved { color: green; }
        .pending { color: orange; }
        .rejected { color: red; }

        .table-wrapper {
            overflow-y: auto;
            max-height: 200px; /* Adjust the height as needed */
        }

        .table-wrapper table {
            width: 100%;
            table-layout: fixed; /* Ensures the columns have the same width */
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: white;
        }

        .table-wrapper thead,
        .table-wrapper tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed; /* Ensures the columns have the same width */
        }

        .table-wrapper tbody {
            display: block;
            max-height: 300px; /* Adjust the height as needed */
            overflow-y: auto;
        }

        .table-wrapper th:nth-child(1),
        .table-wrapper td:nth-child(1) {
            width: 20%; /* 1st column width */
        }

        .table-wrapper th:nth-child(2),
        .table-wrapper td:nth-child(2) {
            width: 50%; /* 2nd column width */
        }

        .table-wrapper th:nth-child(3),
        .table-wrapper td:nth-child(3) {
            width: 10%; /* 3rd column width */
        }

        .table-wrapper th:nth-child(4),
        .table-wrapper td:nth-child(4) {
            width: 20%; /* 4th column width */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#adminSearchForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'admin_search.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        $('#adminSearchResults').html(data);
                    }
                });
            });

            $('#clearButton').on('click', function() {
                $('#adminSearchForm').trigger('reset');
            })

            // Handle row click to fetch and populate form with user details
            $(document).on('click', '#adminSearchResults tr', function() {
                var userID = $(this).data('userid'); // Get the userID from data attribute

                $.ajax({
                    url: 'fetch_user_details.php',
                    type: 'GET',
                    data: { userID: userID },
                    success: function (data) {
                        var userDetails = JSON.parse(data);
                        $('#userDetailsForm').show();
                        $('#userID').val(userDetails.userID);
                        $('#mykadDetail').val(userDetails.mykad);
                        $('#nameDetail').val(userDetails.name);
                        $('#phoneDetail').val(userDetails.phone);
                        $('#birthdateDetail').val(userDetails.birthdate);
                        $('#sexDetail').val(userDetails.sex);
                        $('#membershipNumberDetail').val(userDetails.membership_number);
                        $('#memberSinceDetail').val(userDetails.member_since);
                        $('#applicationStatusDetail').val(userDetails.application_status);
                        $('#commentDetail').val(userDetails.comment);
                    }
                });
            });
        });
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Dashboard</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Dashboard</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <?php if ($authority_level == 2 || $authority_level == 3): ?>
        <li class="nav-item">
            <a class="nav-link active" id="admin-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="true">Admin</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link <?php echo ($authority_level == 1) ? 'active' : ''; ?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="<?php echo ($authority_level == 1) ? 'true' : 'false'; ?>">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="membership-tab" data-toggle="tab" href="#membership" role="tab" aria-controls="membership" aria-selected="false">Membership</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <?php if ($authority_level == 2 || $authority_level == 3): ?>
        <div class="tab-pane fade show active" id="admin" role="tabpanel" aria-labelledby="admin-tab">
            <h3 class="mt-3">Admin Functions</h3>
            <!-- Admin tab-->
            <form id="adminSearchForm">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="mykad">MyKad</label>
                        <input type="text" class="form-control" id="mykad" name="mykad">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="membership_number">Membership Number</label>
                        <input type="text" class="form-control" id="membership_number" name="membership_number">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="application_status">Application Status</label>
                        <select class="form-control" id="application_status" name="application_status">
                            <option value="">Select Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="button" class="btn btn-secondary" id="clearButton">Clear</button>
                    </div>
                </div>
            </form>
           <div class="table-wrapper">
           <table class="table table-bordered table-hover">
               <thead class="thead-dark">
                   <tr>
                       <th>MyKad</th>
                       <th>Name</th>
                       <th>Sex</th>
                       <th>Application Status</th>
                   </tr>
               </thead>
               <tbody id="adminSearchResults">
                   <!-- Search results -->
               </tbody>
           </table>
           </div>
           <!-- User details form -->
            <div id="userDetailsForm" style="display: none; margin-top: 20px;">
            <h3>User Details</h3>
            <form action="update_profile.php" method="post">
                <div class="form-group" style="display: none;">
                    <label for="userID">UserID</label>
                    <input type="text" class="form-control" id="userID" name="userID" readonly>
                </div>
                <div class="form-group">
                    <label for="mykadDetail">MyKad</label>
                    <input type="text" class="form-control" id="mykadDetail" name="mykadDetail">
                </div>
                <div class="form-group">
                    <label for="nameDetail">Name</label>
                    <input type="text" class="form-control" id="nameDetail" name="nameDetail">
                </div>
                <div class="form-group">
                    <label for="phoneDetail">Phone</label>
                    <input type="text" class="form-control" id="phoneDetail" name="phoneDetail">
                </div>
                <div class="form-group">
                    <label for="birthdateDetail">Birthdate</label>
                    <input type="date" class="form-control" id="birthdateDetail" name="birthdateDetail">
                </div>
                <div class="form-group">
                    <label for="sexDetail">Sex</label>
                    <input type="text" class="form-control" id="sexDetail" name="sexDetail">
                </div>
                <div class="form-group">
                    <label for="membershipNumberDetail">Membership Number</label>
                    <input type="text" class="form-control" id="membershipNumberDetail" name="membershipNumberDetail">
                </div>
                <div class="form-group">
                    <label for="memberSinceDetail">Member Since</label>
                    <input type="date" class="form-control" id="memberSinceDetail" name="memberSinceDetail">
                </div>
                <div class="form-group">
                    <label for="applicationStatusDetail">Application Status</label>
                    <!-- <input type="text" class="form-control" id="applicationStatusDetail"> -->
                    <select class="form-control" id="applicationStatusDetail" name="applicationStatusDetail">
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="commentDetail">Comment</label>
                    <textarea class="form-control" id="commentDetail" name="commentDetail"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
            </div>
        </div>
        <?php endif; ?>
        <div class="tab-pane fade <?php echo ($authority_level == 1) ? 'show active' : ''; ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <h3 class="mt-3">Profile</h3>
            <!-- Profile tab -->
            <?php if ($profile_exists): ?>
                <form action="update_profile.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" value="<?php echo $user['username']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $user['email']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="mykad">My Kad Number</label>
                        <input type="text" class="form-control" id="mykad" name="mykad" value="<?php echo $profile['mykad']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $profile['name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $profile['phone']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo $profile['birthdate']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <select class="form-control" id="sex" name="sex">
                            <option value="Male" <?php echo $profile['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $profile['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" value="<?php echo $profile['state']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            <?php else: ?>
                <form action="create_profile.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" value="<?php echo $user['username']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $user['email']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="mykad">My Kad Number</label>
                        <input type="text" class="form-control" id="mykad" name="mykad">
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate">
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <select class="form-control" id="sex" name="sex">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Profile</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="tab-pane fade" id="membership" role="tabpanel" aria-labelledby="membership-tab">
            <h3 class="mt-3">Membership</h3>
            <!-- Membership tab -->
            <?php if ($profile['application_status'] == 'Approved'): ?>
                 <div class="form-group">
                    <label for="mykad">No IC</label>
                    <input type="text" class="form-control" id="mykad" value="<?php echo $profile['mykad']; ?>" readonly>
                 </div>
                 <div class="form-group">
                     <label for="membership_number">Membership Number</label>
                     <input type="text" class="form-control" id="membership_number" value="<?php echo $profile['membership_number']; ?>" readonly>
                 </div>
                 <div class="form-group">
                    <label for="member_since">Member Since</label>
                    <input type="date" class="form-control" id="member_since" value="<?php echo $profile['member_since']; ?>" readonly>
                 </div>            
                 <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" value="Approved" style="color: green;" readonly>
                 </div>
            <?php elseif ($profile['application_status'] == 'Pending'): ?>
                 <div class="form-group">
                    <label for="mykad">No IC</label>
                    <input type="text" class="form-control" id="mykad" value="<?php echo $profile['mykad']; ?>" readonly>
                 </div>
                 <!--<div class="form-group">
                     <label for="membership_number">Membership Number</label>
                     <input type="text" class="form-control" id="membership_number" value="Your application has not been approve yet" readonly style="color:red;">
                 </div>
                 <div class="form-group">
                    <label for="member_since">Member Since</label>
                    <input type="date" class="form-control" id="member_since" value="Your application has not been approve yet" readonly style="color:red;">
                 </div>-->            
                 <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" value="Pending" style="color: orange;" readonly>
                 </div>
            <?php elseif ($profile['application_status'] == 'Rejected'): ?>
                 <form action="apply_membership.php" method="post">
                    <div class="form-group">
                        <label for="mykad">No IC</label>
                        <input type="text" class="form-control" id="mykad" value="<?php echo $profile['mykad']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" id="status" value="Rejected" style="color: red;" readonly>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control" id="comment" style="color:red;" readonly><?php echo $profile['comment']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Re-Apply</button>
                 </form>
            <?php else: ?>
                <form action="apply_membership.php" method="post">
                    <div class="form-group">
                        <p style="color:red;"> Please make sure your profile info is true and click Apply button to apply for membership. The application will be process within 2 to 3 days. You can visit this page to check the application status</p>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
