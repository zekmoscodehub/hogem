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

// Ensure `service_table` exists
$sql_create_table = "CREATE TABLE IF NOT EXISTS service_table (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        branch_name VARCHAR(255),
                        date DATE,
                        preacher VARCHAR(255),
                        message TEXT,
                        attendance INT,
                        ft INT,
                        fb INT,
                        nc INT,
                        children INT
                    )";
mysqli_query($conn, $sql_create_table) or die("Error creating table: " . mysqli_error($conn));

// Function to insert service record
function insertServiceRecord($branch_name, $date, $preacher, $message, $attendance, $ft, $fb, $nc, $children) {
    global $conn;
    $sql = "INSERT INTO service_table (branch_name, date, preacher, message, attendance, ft, fb, nc, children) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssiiiii", $branch_name, $date, $preacher, $message, $attendance, $ft, $fb, $nc, $children);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Record inserted successfully.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error'] = "Error inserting record: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Error in SQL statement.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'] ?? '';
    $preacher = $_POST['preacher'] ?? '';
    $message = $_POST['message'] ?? '';
    $attendance = $_POST['attendance'] ?? 0;
    $ft = $_POST['ft'] ?? 0;
    $fb = $_POST['fb'] ?? 0;
    $nc = $_POST['nc'] ?? 0;
    $children = $_POST['children'] ?? 0;

    insertServiceRecord($branch_name, $date, $preacher, $message, $attendance, $ft, $fb, $nc, $children);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Service Reports</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #343a40;
        }

        .menu {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu .btn {
            font-size: 16px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }

        .alert {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Service Report</h2>

    <!-- Display Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="menu">
        <a href="branch-service-attendance.php" class="btn btn-info"><i class="fas fa-book"></i> View Reports</a>
        <a href="branch_main.php" class="btn btn-info"><i class="fas fa-home"></i> Home</a>
    </div>

    <form method="post">
        <div class="form-group">
            <label for="branch_name">Branch Name:</label>
            <input class="form-control" type="text" id="branch_name" name="branch_name" value="<?php echo htmlspecialchars($branch_name); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input class="form-control" type="date" id="date" name="date" required>
        </div>
        <div class="form-group">
        <label for="preacher">Preacher:</label>
                    <input type="text"class="form-control" id="preacher" name="preacher"required>
</div>
<div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message"class="form-control" placeholder="Message Title and Bible Verse's"required></textarea>
</div>
<div class="form-group">
                    <label for="attendance">Attendance:</label>
                    <input type="number" id="attendance"class="form-control" name="attendance"placeholder="Adults">
</div>
<div class="form-group">
                    <label for="ft">First Timers:<i>(if any)</i></label>
                    <input type="number" id="ft" class="form-control" name="ft">
</div>
<div class="form-group">
                    <label for="fb">Follow Ups:<i>(if any)</i></label>
                    <input type="number" id="fb" class="form-control" name="fb">
</div>
<div class="form-group">
                    <label for="nc">New Converts <i>(if any)</i>:</label>
                    <input type="number" id="nc" class="form-control" name="nc">
</div>
<div class="form-group">
                    <label for="children">Children:</label>
                    <input type="number" id="children" class="form-control" name="children">
</div>
 <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Submit Report</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
