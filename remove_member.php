<?php
// Include database connection and session start here

require 'session.php';
require 'connect.php';

// Check the user's user_type and display content accordingly
if($_SESSION['user_type'] === 'Branch Pastor'){
    header('location:branch_search_members.php');
}
if (!$_SESSION['user_type'] === 'admin') {
    header('location:login.php');
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected member's name from the form
    $selectedMember = $_POST['selected_member'];

    // Write a SQL query to delete the member based on their name
    $deleteQuery = "DELETE FROM staff WHERE username = '$selectedMember'";

    // Execute the query
    if (mysqli_query($conn, $deleteQuery)) {
        echo 'Member deleted successfully.';
        header('location:search_member.php');
    } else {
        echo 'Error removing member: ' . mysqli_error($conn);
    }
}

// Fetch the list of member names from the database
$getMembersQuery = "SELECT username FROM staff";
$memberResult = mysqli_query($conn, $getMembersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Member</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 20rem;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .pd {
            margin: 10px auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            display: block;
        }

        select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-danger,
        .btn-primary {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-danger {
            background-color: #FF6347;
            color: white;
        }

        .btn-primary {
            background-color: #007BFF;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container pd">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this member?');">
        <div class="form-group">
            <span class="h4">Select Member</span>
            <select class="form-control" name="selected_member">
                <?php
                while ($row = mysqli_fetch_assoc($memberResult)) {
                    echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
                }
                ?>
            </select>
        </div>
        <br>
        <button class="btn btn-danger" type="submit">Delete Member</button><br>
    </form>
    <br><a class="btn btn-primary back" href="admin_main.php" value="Go back">Go back</a>
</div>
</body>
</html>
