<?php
// Database connection
require('connect.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display grand total
function displayGrandTotal($branch_name = null) {
    global $conn;
    $sql = "SELECT SUM(total_amount) AS grand_total FROM finance_table";
    if ($branch_name) {
        $sql .= " WHERE branch_name = ?";
    }
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        if ($branch_name) {
            mysqli_stmt_bind_param($stmt, "s", $branch_name);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['grand_total'];
    } else {
        return "Error in SQL statement";
    }
}

// Function to display financial records by date range and branch name
function displayRecords($from_date, $to_date, $branch_name) {
    global $conn;
    $sql = "SELECT * FROM finance_table WHERE Date BETWEEN ? AND ?";
    if ($branch_name && $branch_name != 'all') {
        $sql .= " AND branch_name = ?";
    }
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        if ($branch_name && $branch_name != 'all') {
            mysqli_stmt_bind_param($stmt, "sss", $from_date, $to_date, $branch_name);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $from_date, $to_date);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr><th>Date</th><th>Branch Name</th><th>Tithes Amount</th><th>Offering Amount</th><th>Pledges Amount</th><th>Total Amount</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Date'] . "</td>";
                echo "<td>" . $row['branch_name'] . "</td>";
                echo "<td>" . $row['tithes_amount'] . "</td>";
                echo "<td>" . $row['offering_amount'] . "</td>";
                echo "<td>" . $row['pledges_amount'] . "</td>";
                echo "<td>" . $row['total_amount'] . "</td>";
                echo "<td><button class='delete-btn' onclick='deleteRecord(" . $row['id'] . ")'>Delete</button></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No records found for the selected criteria.";
        }
    } else {
        echo "Error in SQL statement";
    }
}

// Delete record function
function deleteRecord($recordId) {
    global $conn;
    $sql = "DELETE FROM finance_table WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $recordId);
        if (mysqli_stmt_execute($stmt)) {
            echo "Record deleted successfully.";
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        echo "Error in SQL statement";
    }
}

// Check if the delete request is made
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $recordId = $_GET['id'];
    deleteRecord($recordId);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Financial Reports</title>
    <link rel="shortcut icon" href="assets/img/logo.jpg">
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
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
    </style>
</head>
<body>
<div class="main">
    <h2>View Financial Reports</h2>
    <div class="menu">
        <a class="btn btn-outline" href="financials.php"><i class="fas fa-book"></i> Upload Financials</a>
        <a href="admin_main.php"><i class="fas fa-home"></i> Home</a>
    </div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="branch_name">Select Branch Name:</label>
        <select class="form-control" id="branch_name" name="branch_name">
            <option value="all">View All Branches</option>
            <?php
            // Populate dropdown with branch names from database
            $sql = "SELECT DISTINCT branch_name FROM finance_table";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['branch_name'] . "'>" . $row['branch_name'] . "</option>";
            }
            ?>
        </select>
        <label for="from_date">From:</label>
        <input class="form-control calendar" type="date" id="from_date" name="from_date">
        <label for="to_date">To:</label>
        <input class="form-control calendar" type="date" id="to_date" name="to_date">
        <input class="btn btn-primary" type="submit" value="Search">
    </form>

    <?php
    // Display financial records if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $from_date = mysqli_real_escape_string($conn, $_POST['from_date']);
        $to_date = mysqli_real_escape_string($conn, $_POST['to_date']);
        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name']);
        displayRecords($from_date, $to_date, $branch_name);
        if ($branch_name != 'all') {
            echo "Grand Total for " . $branch_name . ": " . displayGrandTotal($branch_name);
        } else {
            echo "Grand Total for All Branches: " . displayGrandTotal();
        }
    }
    ?>

</div>
<script>
    function deleteRecord(recordId) {
        if (confirm("Are you sure you want to delete this record?")) {
            window.location.href = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?action=delete&id=' + recordId;
        }
    }
</script>
</body>
</html>
