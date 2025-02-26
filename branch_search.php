<?php
// session_start();
require ('session.php');
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$username = $_SESSION['username'];

// Fetch user profile data securely
$query = "SELECT title, first_name, last_name, dob, email, user_type, branch_name, branch_region, mobile, address 
          FROM staff WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    extract(array_map('htmlspecialchars', $row));
    $_SESSION['branch_region'] = $branch_region; // Ensure session stores region
} else {
    die("No user data found.");
}

// Ensure branch_region is set in session
if (!isset($_SESSION['branch_region']) || empty($_SESSION['branch_region'])) {
    die("Access denied: No branch region assigned.");
}

$user_branch_region = $_SESSION['branch_region'];

// Fetch members in the same region
$results = [];
$serialNumber = 1;
$query = "SELECT id, username, user_type, branch_region, branch_name, first_name, last_name, sex, address, mobile, email, date_assigned, date_joined 
          FROM staff WHERE branch_region = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $user_branch_region);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $row['serial_number'] = $serialNumber++;
    $results[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
</head>
<body>
<!-- <div class="container mt-5">
    <h2 class="text-center text-primary">Personal Information</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Personal Details</h5>
            <p><strong>Name:</strong> <?= "$title $first_name $last_name" ?></p>
            <p><strong>Date of Birth:</strong> <?= $dob ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Role:</strong> <?= $user_type ?></p>
            <p><strong>Branch Name:</strong> <?= $branch_name ?></p>
            <p><strong>Region:</strong> <?= $branch_region ?></p>
            <p><strong>Mobile:</strong> <?= $mobile ?></p>
            <p><strong>Address:</strong> <?= $address ?></p>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="change_password.php" class="btn btn-success">Change Password</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="branch_search.php" class="btn btn-primary">Search Members</a>
    </div> -->
<div class="container">
<a href="admin_main.php" class="btn btn-primary">Back</a>
    <h2 class="text-center mt-4">Members in <?= htmlspecialchars($user_branch_region) ?></h2>
    <?php if (!empty($results)) : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>ID</th>
                    <th>Town / City</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Branch Name</th>
                    <th>Sex</th>
                    <th>Email</th>
                    <th>Date Assigned</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row) : ?>
                    <tr>
                        <td><?= $row['serial_number'] ?></td>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['branch_region'] ?></td>
                        <td><?= $row['first_name'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['branch_name'] ?></td>
                        <td><?= $row['sex'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['date_assigned'] ?></td>
                        <td>
                            <a href="staff_profile.php?id=<?= urlencode($row['username']) ?>" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="text-center">No members found in your branch region.</p>
    <?php endif; ?>
</div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
