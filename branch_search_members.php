<?php
require('session.php');
require('connect.php');

$results = [];
$serialNumber = 1;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['branch_region'])) {
    $selected_region = mysqli_real_escape_string($conn, $_POST['branch_region']);
    $search_query = ($selected_region === 'all_members') ?
        "SELECT * FROM staff" :
        "SELECT * FROM staff WHERE branch_region = '$selected_region'";

    $result = mysqli_query($conn, $search_query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['serial_number'] = $serialNumber++;
            $row['sex'] = ($row['sex'] === 'Male') ? 'Males' : 'Females';
            $results[] = $row;
        }
    } else {
        $error = 'Error fetching members: ' . mysqli_error($conn);
    }
}

$getregionQuery = "SELECT DISTINCT branch_region FROM staff";
$regionResult = mysqli_query($conn, $getregionQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Members</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 900px; margin: 40px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .btn-primary { background-color: #007bff; color: #fff; border: none; padding: 10px; cursor: pointer; }
        .btn-primary:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_main.php" class="btn btn-secondary" style="float:right;">Home</a>
        <h1>Branch Search</h1>
        <form method="POST">
            <div class="form-group">
                <label for="branch_region">Select Region</label>
                <select class="form-control" name="branch_region" id="branch_region">
                    <option value="all_members">All Members</option>
                    <?php while ($row = mysqli_fetch_assoc($regionResult)) { ?>
                        <option value="<?php echo $row['branch_region']; ?>"><?php echo $row['branch_region']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">View</button>
        </form>
        
        <?php if (!empty($results)) { ?>
            <h3 class="text-center">Branch Members</h3>
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>ID</th>
                        <th>Region</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Branch</th>
                        <th>Sex</th>
                        <th>Email</th>
                        <th>Date Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) { ?>
                        <tr>
                            <td><?php echo $row['serial_number']; ?></td>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['branch_region']; ?></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['branch_name']; ?></td>
                            <td><?php echo $row['sex']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['date_assigned']; ?></td>
                            <td><a href="staff_profile.php?id=<?php echo $row['username']; ?>" class="btn btn-primary">View</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>
</html>
