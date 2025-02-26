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
// Restrict access to Branch Pastors & Assistant Pastors
// $allowed_user_types = ['Branch Pastor'];
// if (!in_array($user_type, $allowed_user_types)) {
//     header('Location: login.php');
//     exit();
// }

// Function to fetch service records
function getServiceRecords($branch_name, $from_date = null, $to_date = null) {
    global $conn;
    
    $sql = "SELECT * FROM service_table WHERE branch_name = ?";
    $params = [$branch_name];
    $types = "s";

    if (!empty($from_date)) {
        $sql .= " AND date >= ?";
        $params[] = $from_date;
        $types .= "s";
    }
    if (!empty($to_date)) {
        $sql .= " AND date <= ?";
        $params[] = $to_date;
        $types .= "s";
    }

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("SQL Prepare Error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return $result;
}

// Handle search filtering
$from_date = $_POST['from_date'] ?? null;
$to_date = $_POST['to_date'] ?? null;
$result = getServiceRecords($branch_name, $from_date, $to_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Service Reports</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <a href="admin_main.php" class="btn btn-primary">Back</a>
    <h2 class="text-center">View Service Records (<?php echo htmlspecialchars($branch_name); ?>)</h2>

    <!-- Search Form -->
    <form method="post" class="row g-3 mt-4">
        <div class="col-md-5">
            <label for="from_date" class="form-label">From Date:</label>
            <input type="date" id="from_date" name="from_date" class="form-control">
        </div>
        <div class="col-md-5">
            <label for="to_date" class="form-label">To Date:</label>
            <input type="date" id="to_date" name="to_date" class="form-control">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Display Service Records -->
    <table class="table table-striped table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Preacher</th>
                <th>Message</th>
                <th>Attendance</th>
                <th>First Timers</th>
                <th>Follow Ups</th>
                <th>New Converts</th>
                <th>Children</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['preacher']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo htmlspecialchars($row['attendance']); ?></td>
                    <td><?php echo htmlspecialchars($row['ft']); ?></td>
                    <td><?php echo htmlspecialchars($row['fb']); ?></td>
                    <td><?php echo htmlspecialchars($row['nc']); ?></td>
                    <td><?php echo htmlspecialchars($row['children']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
