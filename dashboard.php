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
$user_id = $user['id'];
$query_profile = "SELECT * FROM users_profile WHERE userID = '$user_id'";
$result_profile = mysqli_query($conn, $query_profile);
$profile_exists = mysqli_num_rows($result_profile) > 0;

// Fetch membership data from memberships table
$query_membership = "SELECT * FROM memberships WHERE userID = '$user_id'";
$result_membership = mysqli_query($conn, $query_membership);
$membership_exists = mysqli_num_rows($result_membership) > 0;

if ($membership_exists) {
    $membership = mysqli_fetch_assoc($result_membership);
}

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
        <?php if ($authority_level == 2 || $authority_leve = 3) : ?>
        <div class="tab-pane fade show active" id="admin" role="tabpanel" aria-labelledby="admin-tab">
            <h3 class="mt-3">Admin Functions</h3>
            <!-- Add your admin functionalities here -->
            <p>Placeholder for admin functions</p>
        </div>
        <?php endif; ?>
        <div class="tab-pane fade <?php echo ($authority_level == 1) ? 'show active' : ''; ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <h3 class="mt-3">Profile</h3>
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
            <?php if ($membership_exists): ?>
                   <?php if ($membership['status'] == 'Rejected'): ?>
                            <form action="reapply_membership.php" method="post">
                                <div class="form-group">
                                    <label for="membership_number">Membership Number</label>
                                    <input type="text" class="form-control" id="membership_number" value="Your application has not been approve yet" readonly style="color:red;">
                                </div>
                                <div class="form-group">
                                    <label for="mykad">No IC</label>
                                    <input type="text" class="form-control" id="mykad" value="<?php echo $membership['mykad']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="member_since">Member Since</label>
                                    <input type="text" class="form-control" id="member_since" value="<?php echo $membership['member_since']; ?>" readonly>
                                </div>            
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input type="text" class="form-control" id="status" value="<?php echo $membership['status']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="comment">Comment</label>
                                    <textarea class="form-control" id="comment" readonly><?php echo $membership['comment']; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Re-Apply</button>
                            </form>
                   <?php else: ?>
                        <div class="form-group">
                            <label for="membership_number">Membership Number</label>
                            <?php if ($membership['status'] == 'Approved'): ?>
                                <input type="text" class="form-control" id="membership_number" value="<?php echo $membership['membership_number']; ?>" readonly>
                            <?php else: ?>
                                <input type="text" class="form-control" id="membership_number" value="Your application has not been approve yet" readonly style="color:red;">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="mykad">No IC</label>
                            <input type="text" class="form-control" id="mykad" value="<?php echo $membership['mykad']; ?>" readonly>
                            </div>
                        <div class="form-group">
                            <label for="member_since">Member Since</label>
                            <input type="text" class="form-control" id="member_since" value="<?php echo $membership['member_since']; ?>" readonly>
                        </div>            
                        <div class="form-group">
                            <label for="status">Status</label>
                            <input type="text" class="form-control" id="status" value="<?php echo $membership['status']; ?>" readonly>
                        </div>
                   <?php endif; ?> 
            <?php else: ?>
                <form action="apply_membership.php" method="post">
                    <div class="form-group">
                        <label for="mykad">My Kad</label>
                        <input type="text" class="form-control" id="mykad" name="mykad">
                    </div>
                    <button type="submit" class="btn btn-primary">Apply</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
