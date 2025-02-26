<?php
require('session.php');
require('connect.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_staff'])) {
        $username = $_POST['username'];

        // Validate and sanitize the input
        if (ctype_digit($username)) {
            // Perform the deletion query
            $delete_query = "DELETE FROM staff WHERE id = $username";
            $delete_result = mysqli_query($conn, $delete_query);

            if ($delete_result) {
                // Staff member deleted successfully
                header("Location: search_staff.php");
                exit();
            } else {
                $error = 'Error deleting the member: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Invalid name format.';
        }
    }
}

// Query to fetch staff details
$search_query = "SELECT id, username, user_type, branch_region, branch_name, first_name, last_name, date_assigned, email, sex, dob, mobile, address, date_joined FROM staff";
$result = mysqli_query($conn, $search_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Members</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 95%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-group {
            margin-top: 10px;
        }

        .btn {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="logout.php" class="btn btn-primary">Logout</a>
    <h1>House Of Grace Members</h1>
    <table>
        <thead>
        <tr>
            <th>Branch</th>
            <th>Region</th>
            <th>Email</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>Mobile</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['branch_name']; ?></td>
                <td><?php echo $row['branch_region']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td class="btn-group">
                    <a href="staff_profile.php?id=<?php echo $row['username']; ?>" class="btn btn-primary" disabled>View</a>
                    <a href="update_member.php?id=<?php echo $row['username']; ?>" class="btn btn-success" disabled>Update</a>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="username" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_staff" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this staff?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <div class="btn-group">
        <a href="register.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Member</a>
        <a href="remove_member.php" class="btn btn-danger"><i class="fas fa-minus"></i> Delete Member</a>
        <a href="admin_main.php" class="btn btn-info">Back to Home</a>
    </div>
</div>
</body>
</html>


