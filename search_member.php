<?php
require('session.php');
require('connect.php');

// Redirect if user type is Pastor or Branch_Pastor
if ($_SESSION['user_type'] === 'Pastor' || $_SESSION['user_type'] === 'Branch_Pastor'|| $_SESSION['user_type'] === 'Assistant_Pastor' ) {
    header('location: branch_search_members.php');
}

// Initialize variables
$error = '';
$results = [];

// Fetch branch regions for dropdown
$getRegionQuery = "SELECT DISTINCT branch_region FROM staff";
$regionResult = mysqli_query($conn, $getRegionQuery);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['branch_region'])) {
    $selectedRegion = $_POST['branch_region'];

    if ($selectedRegion === 'all_members') {
        $searchQuery = "SELECT * FROM staff";
    } else {
        $searchQuery = "SELECT * FROM staff WHERE branch_region = '$selectedRegion'";
    }

    $result = mysqli_query($conn, $searchQuery);

    if (!$result) {
        $error = 'Error fetching members: ' . mysqli_error($conn);
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
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
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            margin: 0 5px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <h1>View by Branch</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="form-group">
            <label for="branch_region">Select District:</label>
            <select class="form-control" name="branch_region">
                <option value="all_members">All Members</option>
                <?php while ($row = mysqli_fetch_assoc($regionResult)) : ?>
                    <option value="<?php echo $row['branch_region']; ?>"><?php echo $row['branch_region']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">View Members</button>
    </form>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php elseif (!empty($results)) : ?>
        <h2>Members</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Branch Name</th>
                <th>Sex</th>
                <th>Email</th>
                <th>Date Assigned</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $row) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['branch_name']; ?></td>
                    <td><?php echo $row['sex']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['date_assigned']; ?></td>
                    <td>
                        <a href="staff_profile.php?id=<?php echo $row['username']; ?>" class="btn btn-primary">View</a>
                        <!-- Add other actions as needed -->
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="btn-group">
        <a href="register.php" class="btn btn-success"><i class="fas fa-plus"></i> Enroll Member</a>
        <a href="remove_member.php" class="btn btn-danger"><i class="fas fa-minus"></i> Delete Member</a>
        <a href="admin_main.php" class="btn btn-info">Back to Home</a>
    </div>
</div>
</body>
</html>
