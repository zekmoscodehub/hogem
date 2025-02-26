<?php
session_start(); // Starting the session

// Database connection
require('connect.php');
// Check if the user is logged in and has admin privileges
if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    // Redirect to another page or display an error message
    header("Location:index.php");
    exit(); // Ensure script execution stops
}
// Function to fetch all service records
function getAllServiceRecords() {
    global $conn;
    $sql = "SELECT * FROM service_table";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Function to fetch service records based on search criteria
function searchServiceRecords($branch_name, $from_date, $to_date) {
    global $conn;
    $sql = "SELECT * FROM service_table WHERE 1";
    if (!empty($branch_name)) {
        $sql .= " AND branch_name = '$branch_name'";
    }
    if (!empty($from_date)) {
        $sql .= " AND date >= '$from_date'";
    }
    if (!empty($to_date)) {
        $sql .= " AND date <= '$to_date'";
    }
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Function to delete a service record
function deleteServiceRecord($id) {
    global $conn;
    $sql = "DELETE FROM service_table WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Get all service records by default
$result = getAllServiceRecords();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_name = isset($_POST['branch_name']) ? $_POST['branch_name'] : "";
    $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
    $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : "";
    $result = searchServiceRecords($branch_name, $from_date, $to_date);
}

// Function to calculate top 10 performing branches
function getTopPerformingBranches() {
    global $conn;
    $sql = "SELECT branch_name, SUM(attendance +children) AS total_attendance FROM service_table GROUP BY branch_name ORDER BY total_attendance DESC LIMIT 10";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Get top 10 performing branches
$top_branches = getTopPerformingBranches();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Service Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-+fqpP2ZDxLg7gyjtCprz+TazlK9pCNhYLlORokjR5tXUtn5ax/+F/Jhb3/Th4XfjReJsqXwDCAlcZ3cDk2Qlg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h2 {
            margin-top: 0;
        }

        .menu {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .menu a {
            margin: 0 10px;
            padding: 5px 6px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #0056b3;
        }

        form {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 6px 6px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            /* width: 100%; */
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
           max-width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        ol {
            padding-left: 10px;
        }

        ol li {
            margin-bottom: 7px;
        }
        .main-form{
            width:500px;


        }
    </style>
</head>
<body>
    <h2>Service Records</h2>
    <div class="menu">
        <a href="service.php"><i class="fas fa-book"></i>Enter Service Report</a>
        <a href="admin-services-report.php" class="btn btn-success">View Uploaded Reports</a>
        <a href="admin_main.php"><i class="fas fa-home"></i>Home</a>
    </div>
    <div class="main-form">
    <!-- Search form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="branch_name">Branch Name:</label>
        <input type="text" id="branch_name" name="branch_name">
        <label for="from_date">From Date:</label>
        <input type="date" id="from_date" name="from_date">
        <label for="to_date">To Date:</label>
        <input type="date" id="to_date" name="to_date">
        <input type="submit" value="Search">
    </form>
    </div>
    <!-- Display service records -->
    <table >
        <tr>
            <th>Name</th>
            <th>Service Date</th>
            <th>Preacher</th>
            <th>Message</th>
            <th>Adults</th>
            <th>First Timers</th>
            <th>Follow Ups</th>
            <th>New Converts</th>
            <th>Children</th>
            <th>Action</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['branch_name'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['preacher'] . "</td>";
            echo "<td>" . $row['message'] . "</td>";
            echo "<td>" . $row['attendance'] . "</td>";
            echo "<td>" . $row['ft'] . "</td>";
            echo "<td>" . $row['fb'] . "</td>";
            echo "<td>" . $row['nc'] . "</td>";
            echo "<td>" . $row['children'] . "</td>";
            echo "<td><form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'><input type='hidden' name='id' value='".$row['id']."'><input type='submit' name='delete' value='Delete'></form></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Display top 10 performing branches -->
    <h2>Top 10 Performing Branches by Attendance</h2>
    <ol>
        <?php
        while ($row = mysqli_fetch_assoc($top_branches)) {
            echo "<li>" . $row['branch_name'] . " - Total Attendance including Children: " . $row['total_attendance'] . "</li>";
        }
        ?>
    </ol>
</body>
</html>

<?php
// Handle delete action
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    if (deleteServiceRecord($id)) {
        echo "<script>alert('Record deleted successfully.');</script>";
        // header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
        exit();
    } else {
        echo "<script>alert('Error deleting record.');</script>";
    }
}
?>
