<?php
require('session.php');
require('connect.php');

// Check the user's user_type and redirect accordingly
$user_type = $_SESSION['user_type'] ?? '';
$redirects = [
    'Branch_Pastor' => 'Branch_Pastor.php',
    'admin' => 'admin_main.php',
    'Assistant_Pastor' => 'ass.pastor_main.php'
];

if (isset($redirects[$user_type])) {
    header('location:' . $redirects[$user_type]);
    exit;
} elseif ($user_type !== 'Member') {
    header('location:index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta property="og:title" content="HOUSE OF GRACE" />
    <meta property="og:description" content="Church Management System" />
    <meta property="og:site_name" content="HOUSE OF GRACE" />
    <link rel="shortcut icon" href="assets/img/logo.jpg">
    <title>Member</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <!-- <link rel="stylesheet" href="assets/css/admin.css"> -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: #333;
            color: #fff;
            height: 100vh;
            padding: 20px;
            position: fixed;
            transition: all 0.3s;
        }

        .sidebar a {
            display: block;
            color: #ddd;
            padding: 10px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #575757;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }

        .navbar {
            background: #444;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin:0.5em;
        }

        .btn:hover {
            background: #0056b3;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
                left: -200px;
            }
            .main-content {
                margin-left: 0;
            }
            .navbar .menu-toggle {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <a href="member-main.php">Dashboard</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php" style="color: red; font-weight: bold;">Logout</a>
        </div>
        <div class="main-content">
            <div class="navbar">
                <span>Welcome, <a href='member-main.php'><?php echo $_SESSION['username']; ?></a>!</span>
            </div>
            <div class="card">
                
                <h2>Personal Information</h2>
                <?php
                require 'connect.php';
                $username = $_SESSION['username'];
                $query = "SELECT title, first_name, last_name, dob, email, user_type, branch_name, branch_region, mobile, address FROM staff WHERE username = '$username'";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo "<p>Name: {$row['title']} {$row['first_name']} {$row['last_name']}</p>
                          <p>Date of Birth: {$row['dob']}</p>
                          <p>Email: {$row['email']}</p>
                          <p>Role: {$row['user_type']}</p>
                          <p>Branch: {$row['branch_name']} ({$row['branch_region']})</p>
                          <p>Mobile: {$row['mobile']}</p>
                          <p>Address: {$row['address']}</p>";
                } else {
                    echo "<p>No user data found.</p>";
                }
                mysqli_free_result($result);
                mysqli_close($conn);
                ?>
            </div>
            <div class="card">
                <h2>Account Status</h2>
                <button class="btn btn-success">Active</button>
            </div>
            <div class="card">
                <h2>Church Reports</h2>
                <button class="btn btn-primary" disabled>View Attendance</button>
                <button class="btn btn-primary" disabled>Enter Report</button>
            </div>
            <footer style="text-align: center; margin-top: 20px;">
                <p>&copy; 2025 House Of Grace Church - Ghana</p>
            </footer>
        </div>
    </div>
</body>
</html>
