<?php
require 'session.php';
require 'connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['branch_region'])) {
        $selected_region = $_POST['branch_region'];

        // Prepare the query to fetch staff details for the selected region
        if ($selected_region == 'all_members') {
            $search_query = "SELECT username,user_type,branch_region, branch_name, address,mobile,email, date_joined FROM staff";
        } else {
            $search_query = "SELECT username,user_type,branch_region, branch_name, address,mobile,email, date_joined FROM staff WHERE branch_region = '$selected_region'";
        }

        // Perform the query
        $result = mysqli_query($conn, $search_query);
        if (!$result) {
            $error = 'Error fetching members: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Please select a region.';
    }
}
$getregionQuery = "SELECT branch_region FROM staff";
$regionResult = mysqli_query($conn, $getregionQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Branch Members</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
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

        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-primary {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>House Of Grace Branch Churches</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="form-group">
            <label for="branch_region"><h2 class="h2">Select District</h2></label>
            <select class="form-control" name="branch_region">
                <?php
                while ($row = mysqli_fetch_assoc($regionResult)) {
                    echo '<option value="' . $row['branch_region'] . '">' . $row['branch_region'] . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (isset($result)) : ?>
        <table>
            <thead>
            <tr>
                <th>District</th>
                <th>Branch Name</th>
                <th>Address</th>
                <th>Role</th>
                <th>Pastor</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['branch_region']; ?></td>
                    <td><?php echo $row['branch_name']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['user_type']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['mobile']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <!-- Membership button -->
                        <a href="membership.php?region=<?php echo urlencode($row['branch_region']); ?>"
                           class="btn btn-primary">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="register.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Branch</a>
        <a href="admin_main.php" class="btn btn-info">Back to Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<script src="assets/plugins/jquery/jquery.min.js"></script>
</body>
</html>


