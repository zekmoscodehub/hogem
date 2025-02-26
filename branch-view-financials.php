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

// Function to display grand total
function displayGrandTotal($branch_name) {
    global $conn;
    $sql = "SELECT SUM(total_amount) AS grand_total FROM finance_table WHERE branch_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $branch_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['grand_total'] ?? 0;
    }
    return "Error retrieving grand total.";
}

// Function to display financial records
function displayRecords($from_date, $to_date, $branch_name) {
    global $conn;
    $sql = "SELECT * FROM finance_table WHERE branch_name = ? AND Date BETWEEN ? AND ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $branch_name, $from_date, $to_date);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead class='thead-dark'><tr><th>Date</th><th>Tithes</th><th>Offering</th><th>Pledges</th><th>Total</th></tr></thead><tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tithes_amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['offering_amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pledges_amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info'>No records found for the selected criteria.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error retrieving records.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Records</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <a href="admin_main.php"class="btn btn-success">Back</a>
    <h2 class="text-center mb-4">Financial Records for <?php echo htmlspecialchars($branch_name); ?></h2>
    <form method="post" class="mb-4 p-4 border rounded bg-light">
        <div class="form-group">
            <label for="from_date">From Date:</label>
            <input type="date" class="form-control" id="from_date" name="from_date" required>
        </div>
        <div class="form-group">
            <label for="to_date">To Date:</label>
            <input type="date" class="form-control" id="to_date" name="to_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';

        if (!empty($from_date) && !empty($to_date)) {
            displayRecords($from_date, $to_date, $branch_name);
            echo "<div class='alert alert-success'>Grand Total: " . displayGrandTotal($branch_name) . "</div>";
        } else {
            echo "<div class='alert alert-warning'>Please select a valid date range.</div>";
        }
    }
    ?>
</div>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
