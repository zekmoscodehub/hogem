
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
$user_branch_name = $_SESSION['branch_name'];
$search_branch = $user_branch_name;

// Initialize variables
$successMessage = '';
$error = '';

// Handle delete request
if (isset($_POST['delete_report'])) {
    $report_id = $_POST['delete_report'];

    // Query to delete the report from the database
    $delete_query = "DELETE FROM service_reports WHERE service_report_id = '$report_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        $successMessage = "Report successfully deleted";
    } else {
        $error = 'Error deleting the report: ' . mysqli_error($conn);
    }
}

// Fetch uploaded reports from the database
$fetch_query = "SELECT * FROM service_reports";

// If searching by branch name
if (isset($_GET['search_branch']) && !empty($_GET['search_branch'])) {
    $search_branch = mysqli_real_escape_string($conn, $_GET['search_branch']);
    $fetch_query .= " WHERE branch_name LIKE '%$search_branch%'";
}

$fetch_result = mysqli_query($conn, $fetch_query);

// If there are reports
$reports = [];
if (mysqli_num_rows($fetch_result) > 0) {
    while ($row = mysqli_fetch_assoc($fetch_result)) {
        $reports[] = $row;
    }
}

// If there was an error, handle it appropriately
if (!empty($error)) {
    // Handle the error
}

// Display the success message if any
if (!empty($successMessage)) {
    // Display the success message
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Service Reports</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <style>
     
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .main {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .menu {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .menu a {
            font-family: sans-serif;
            text-decoration: none;
            color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #007bff;
            color: #fff;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            font-weight: bold;
        }

        input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
        }

        .calendar {
            margin-top: 5px;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
   
          .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-search {
            width: 100%;
        }

        @media (max-width: 576px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
    <h5><a href="branch-service.php"class="btn btn-info text-center">Enter Service Report</a></h5><br>
        
        <div class="container">
        <?php
        // Display validation error, if any
        if (!empty($successMessage)) {
            echo '<p class="text-success text-center">' . $successMessage . '</p>';
        }
        if (!empty($error)) {
            echo '<p class="text-danger text-center">' . $error . '</p>';
        }
        ?>
        <div class="forms">


<div class="upload-form">
<h3 class="text-center mb-4"> Upload Service report Form</h3>
        <h5 class="text-center">(PDF, DOCX, JPG etc. )</h5>
        <form method="post" action="uploads.php" enctype="multipart/form-data">

        <div class="form-group">
            <label for="branch_name">Branch Name:</label>
            <input class="form-control" type="text" id="branch_name" name="branch_name" value="<?php echo htmlspecialchars($branch_name); ?>" readonly>
        </div>

            <div class="form-group">
                <label for="note_title">Service Date</label>
                <input type="date" id="note_title" class="form-control" name="note_title" required>
            </div>

            <div class="form-group">
                <!-- <label for="file_upload">Upload Report</label> -->
                <input type="file" id="file_upload" class="file-upload" name="file_upload" accept=".pdf, .jpeg, .jpg, .png, .gif, .docx" required>
                <label for="file_upload" class="file-upload-label" style="margin-top:20px;"><sub><i>pdf, jpeg, png, gif, docx etc.</i></sub></label><br>
                <button type="submit" class="btn btn-primary btn-block"style="margin-top:20px;">Upload File</button>
            </div>

           
        </form>
</div>
   <hr>
<div class="view-form">
        <h3 class="text-center mb-4">View Uploaded Service Reports</h3>

        <!-- Search form -->
        <form method="get" action="branch-services-report.php">
        <div class="form-group">
            <label for="branch_name">Branch Name:</label>
            <input class="form-control" type="text" id="branch_name" name="search_branch" value="<?php echo htmlspecialchars($branch_name); ?>" readonly>
        </div>
            <button type="submit" class="btn btn-info btn-block">Search</button>
        </form>
</div>
        </div>

        <hr>

        <!-- Display uploaded reports in table -->
        <div class="table-responsive">
            <?php if (!empty($reports)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date uploaded</th>
                        <th>Branch Name</th>
                        <th>Service Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo $report['date']; ?></td>
                        <td><?php echo $report['branch_name']; ?></td>
                        <td><?php echo $report['note_title']; ?></td>
                        <td>
                            <a href="<?php echo $report['file_path']; ?>" class="btn btn-primary btn-sm disabled" download>Download</a>
                            <form method="post" action="delete_report.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                <input type="hidden" name="delete_report" value="<?php echo $report['service_report_id']; ?>">
                                <button type="submit" title="You do not have privileges for this task" class="btn btn-danger btn-sm disabled">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-center">No reports found.</p>
            <?php endif; ?>
        </div>

        <hr>

        <a href="admin_main.php" class="btn btn-success">Back to Home</a>
    </div>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>
