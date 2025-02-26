<?php
// Database connection
require 'connect.php';
require 'session.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Check if the user is logged in and has admin privileges
if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    // Redirect to another page or display an error message
    header("Location:index.php");
    exit(); // Ensure script execution stops
}
// Function to calculate and update total amount
function updateTotal() {
    global $conn;
    $sql = "UPDATE finance_table SET total_amount = tithes_amount + offering_amount + pledges_amount";
    mysqli_query($conn, $sql);
}

// Function to display grand total
function displayGrandTotal() {
    global $conn;
    $sql = "SELECT SUM(total_amount) AS grand_total FROM finance_table";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['grand_total'];
}

// Insert data into database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $branch_name = $_POST['branch_name'];
    $tithes_amount = $_POST['tithes_amount'];
    $offering_amount = $_POST['offering_amount'];
    $pledges_amount = $_POST['pledges_amount'];

    // Check if the branch name exists in the staff table
    $check_branch_sql = "SELECT * FROM staff WHERE branch_name = '$branch_name'";
    $check_branch_result = mysqli_query($conn, $check_branch_sql);
    if (mysqli_num_rows($check_branch_result) > 0) {
        // Branch name exists, proceed with insertion
        $sql = "INSERT INTO finance_table (Date, branch_name, tithes_amount, offering_amount, pledges_amount) VALUES ('$date', '$branch_name', '$tithes_amount', '$offering_amount', '$pledges_amount')";

        if (mysqli_query($conn, $sql)) {
            updateTotal();
            $successMsg = "<p>New record entered successfully for $branch_name</p>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $errorMsg = "<p>Branch name '$branch_name' does not exist in our Database.</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Records</title>
    <link rel="shortcut icon" href="assets/img/logo.jpg">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-top: 50px;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .main {
            margin-top:100px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction:column;
        }

        .cardr {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cardr .btn {
            margin-bottom: 10px;
        }

        .cardr h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        .cardr form label {
            font-weight: bold;
        }

        .cardr form input[type="date"],
        .cardr form input[type="text"],
        .cardr form input[type="number"],
        .cardr form input[type="submit"],
        .cardr form a.btn {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="main">
         <div> <a class="btn btn-secondary" href="view-financials.php">View Financial Report</a></div><!<!-- lINK TO VIEW REPORTS -->
        <h1 class="title"> Financial Report</h1>
    <div class="cardr">
     
        <?php
        if (!empty($successMsg)) {
            echo '<p class="text-green"style="text-align:center; justify-content:center;font-size:16px;margin-left:40px;">' . $successMsg . '</p>';
        }
        if (!empty($errorMsg)) {
            echo '<p class="text-red" style="text-align:center; justify-content:center;font-size:16px;margin-left:40px;">' . $errorMsg . '</p>';
        }
        ?>

      
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <label for="date">Date:</label>
            <input class="form-control" type="date" id="date" name="date" required><br><br>
            <label for="branch_name">Branch Name:</label>
            <input class="form-control" type="text" id="branch_name" name="branch_name" required><br><br>
            <label for="tithes_amount">Tithes Amount:</label>
            <input class="form-control" type="number" id="tithes_amount" name="tithes_amount" min="0" required><br><br>
            <label for="offering_amount">Offering Amount:</label>
            <input class="form-control" type="number" id="offering_amount" name="offering_amount" min="0" required><br><br>
            <label for="pledges_amount">Pledges Amount:</label>
            <input class="form-control" type="number" id="pledges_amount" name="pledges_amount" min="0" required><br><br>
            <input class="btn btn-success" type="submit" value="Submit"> <a class="btn btn-primary " href="admin_main.php">Home</a>
            <br>

        </form>

    </div>
</div>
</body>
</html>