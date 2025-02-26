<?php
require('session.php');
require('connect.php');

// Check if the user is logged in and has admin privileges
// if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Branch Pastor' || $_SESSION['user_type'] !== 'Admin'|| $_SESSION['user_type'] !== 'Assistant Pastor') {
//     // Redirect to another page or display an error message
//     header("Location:index.php");
//     exit(); // Ensure script execution stops
// }

if ($_SESSION['user_type'] === 'Pastor') {
    header('location:branch_search_members.php');
}
if ($_SESSION['user_type'] === 'Assistant Pastor') {
    header('location:branch_search_members.php');
}

if ($_SESSION['user_type'] === 'Pastor') {
    header('location:branch_search_members.php');
}
if ($_SESSION['user_type'] === 'Branch Pastor') {
    header('location:branch_search_members.php');
}
// Include your database connection script
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $branch_region = "";

    // Initialize an empty array to store results and a counter for serial numbers
    $results = [];
    $serialNumber = 1;

    // List of class tables
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['branch_region'])) {
            $selected_region = $_POST['branch_region'];

            // Prepare the query to fetch staff details for the selected region
            if ($selected_region == 'all_members') {
                $search_query = "SELECT id,username,user_type,branch_region, branch_name,first_name,last_name,sex, address,mobile,email,date_assigned, date_joined FROM staff";
            } else {
                $search_query = "SELECT id,username,user_type,branch_region, branch_name,first_name,last_name, sex, address,mobile,email, date_assigned,date_joined FROM staff WHERE branch_region = '$selected_region'";
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

    // Fetch and store results
    while ($row = mysqli_fetch_assoc($result)) {
        // Add the serial number to the row
        $row['serial_number'] = $serialNumber;

        // Fetch and display users by their sex ("Males", "Females")
        if ($row['sex'] == 'Male' && $row['branch_name'] == 'branch_name') {
            $row['sex'] = 'Males';
        } elseif ($row['sex'] == 'Female' && $row['branch_name'] == 'branch_name') {
            $row['sex'] = 'Females';
        }

        $results[] = $row;
        $serialNumber++; // Increment the serial number
    }
} else {
    // Fetch data from the selected class table
    $search_query = "SELECT * FROM staff";
    $result = mysqli_query($conn, $search_query);

    // Fetch and store results
    while ($row = mysqli_fetch_assoc($result)) {
        // Add the serial number to the row
        // Fetch and display users by their sex ("Males", "Females")
        if ($row['sex'] == 'Male' && $row['branch_name'] == 'branch_name') {
            $row['sex'] = 'Males';
        } elseif ($row['sex'] == 'Female' && $row['branch_name'] == 'branch_name') {
            $row['sex'] = 'Females';
        }

//        $row['serial_number'] = $serialNumber;
//        $results[] = $row;
//        $serialNumber++; // Increment the serial number
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
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="WGC" content="WGC" /> <!-- website name -->
        <meta property="WGC" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="Watered Garden Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
        <title>Members</title>
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/plugins/feather/feather.css">
        <link rel="stylesheet" href="assets/plugins/icons/flags/flags.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                min-width: 50vmin;
                height: 100vh;
                flex-wrap: wrap;


            }

            .container {
                min-width: 60vmin;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .box1{
                background-color:#fff;
                margin: 10px 10px;


            }

            h1 {
                text-align: center;
                margin-bottom: 20px;
            }

            img.profile-photo {
                border-radius: 50%;
                height: 150px;
                width: 150px;
                margin: 20px auto;
                display: block;
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

            a.back-link {
                display: inline-block;
                margin-top: 20px;
                color: #007bff;
                text-decoration: none;
            }

            a.back-link:hover {
                text-decoration: underline;
            }

            .form-box {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
        </style>
    </head>
    <body>
        <div class="form-box">

            <div class="container" style="margin-top: 40px;">
                <a href="logout.php" style="background-color: blue; color: #fff; text-decoration: none; border-radius: 20px; padding: 8px; margin-left: 90%;">Logout</a>
                <h1>Select category to view</h1>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" action="member-view.php" method="POST" class="searc">
                <div class="form-group">
                    <span class="h5">Select Region</span>
                    <select class="form-control" name="branch_region">
                        
                        <?php
                        while ($row = mysqli_fetch_assoc($regionResult)) {
                          
                            echo '<option value="' . $row['branch_region'] . '">' . $row['branch_region'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
               
                <button class="btn btn-danger" type="submit">View</button>
            </form>
                <br>
<?php if (!empty($results)) : ?>
                    <table>
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Branch Name</th>
                                <th>Sex</th> 
                                <th>Email</th>
                                <th>Date assigned</th> <!-- Add Sex column -->
                                <!-- Add Sex column -->
                                <!-- Add other columns you want to display -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php foreach ($results as $row) : ?>
                                <tr>
                                    <td><?php echo $row['serial_number']; ?></td>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['first_name']; ?></td>
                                    <td><?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['branch_name']; ?></td>
                                    <td><?php echo $row['sex']; ?></td> 
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['date_assigned']; ?></td>
                                    <!-- Display the sex column -->
                                    <!-- Add other cells for columns you want to display -->
                                    <td>
                                        <a href="staff_profile.php?id=<?php echo $row['username']; ?>" class="btn btn-primary">View</a>
                                        <!--<a href="update_member.php?id=<?php echo $row['username']; ?>" id="admin-content" class="btn btn-success">Update</a>-->
                                        <!--<a href="delete_student.php?id=<?php echo $row['username']; ?>&class=<?php echo $search_member; ?>" onclick="return confirm('Are you sure you want to delete this student?')" id="content" class="btn btn-danger">Delete</a>-->
                                    </td>
                                </tr>
    <?php endforeach; ?>
                        </tbody>
                    </table>
                        <?php endif; ?>
                <div>
                    <span>
                    <li style="list-style:none;"><a href="register.php"><i class="fas fa-plus"style="margin-right:10px;color:green;"></i>Enroll Member</a></li>
                    <li style="list-style:none;"><a href="remove_member.php"><i class="fas fa-minus"style="margin-right:10px;color:red;"></i>Delete Member</a></li>
                    <a href="admin_main.php" class="btn btn-success">Back to Home</a></span>
                </div>
            </div>
            <hr>
            <h2 class="title"style="color:blue;">All Members</h2>
            <div class="row" >
                <div class="col col-6">
                    <div class="box1">
                        <h3>Females</h3>
<?php
require 'connect.php';

// Include your database connection script
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_region = $_POST['branch_region'];

    // Initialize an empty array to store results and a counter for serial numbers
    $results = [];
    $serialNumber = 1;

    // List of class tables
    if ($branch_region === 'branch_region') {
        // Fetch data from all class tables and combine the results
        $class_tables = ['Tamale', 'Accra', 'Brohafo', 'Westhern', 'Accra', 'Kumasi', 'Easten', 'Bolgatanga', 'Savana_region', 'Southen', 'Central', 'Tamale'];

        foreach ($class_tables as $class_table) {
            // Fetch data from each class table
            $search_query = "SELECT * FROM staff WHERE sex = 'female' AND branch_region = 'branch_region'"; // Only boys
            $result = mysqli_query($conn, $search_query);

            // Fetch and store results
            while ($row = mysqli_fetch_assoc($result)) {
                // Add the serial number to the row
                $row['serial_number'] = $serialNumber;
                $results[] = $row;
                $serialNumber++; // Increment the serial number
            }
        }
    } else {
        // Fetch data from the selected class table
        $search_query = "SELECT * FROM staff WHERE sex = 'female'"; // Only boys
        $result = mysqli_query($conn, $search_query);

        // Fetch and store results
        while ($row = mysqli_fetch_assoc($result)) {
            // Add the serial number to the row
            $row['serial_number'] = $serialNumber;
            $results[] = $row;
            $serialNumber++; // Increment the serial number
        }
    }
}
?>

                        <?php if (!empty($results)) : ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Branch</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>User type</th>
                                        <th>Sex</th> <!-- Add Sex column -->
                                        <!-- Add other columns you want to display -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php foreach ($results as $row) : ?>
                                        <tr>
                                            <td><?php echo $row['serial_number']; ?></td>
                                            <td><?php echo $row['branch_name']; ?></td>
                                            <td><?php echo $row['first_name']; ?></td>
                                            <td><?php echo $row['last_name']; ?></td>
                                            <td><?php echo $row['user_type']; ?></td>
                                            <td><?php echo $row['sex']; ?></td> <!-- Display the sex column -->
                                            <!-- Add other cells for columns you want to display -->
                                            <td>
                                                <a href="staff_profile.php?id=<?php echo $row['username']; ?>" class="btn btn-primary">View</a>
                            
                                        </tr>
    <?php endforeach; ?>
                                </tbody>
                            </table>
<?php endif; ?>
                    </div>
                </div>
                <div class="col" >
                    <div class="box1" style="margin:30px;">
                        <h3>Males</h3>
                        <?php
                        require 'connect.php';

// Include your database connection script
// Check if the form is submitted
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $search_class = $_POST['branch_region'];

                            // Initialize an empty array to store results and a counter for serial numbers
                            $results = [];
                            $serialNumber = 1;

                            // List of class tables
                            if ($search_class == 'branch_region') {
                                // Fetch data from all class tables and combine the results
                                $class_tables = ['Northen', 'Upper Volta', 'Brohafo', 'Westhern', 'Greter_accra', 'Southen', 'Easten', 'Upper_East', 'Savana_region', 'Southen', 'Central',];

                                foreach ($class_tables as $class_table) {
                                    // Fetch data from each class table
                                    $search_query = "SELECT * FROM $class_table WHERE sex = 'Male'"; // Only boys
                                    $result = mysqli_query($conn, $search_query);

                                    // Fetch and store results
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Add the serial number to the row
                                        $row['serial_number'] = $serialNumber;
                                        $results[] = $row;
                                        $serialNumber++; // Increment the serial number
                                    }
                                }
                            } else {
                                // Fetch data from the selected class table
                                $search_query = "SELECT * FROM staff WHERE sex = 'Male'"; // Only boys
                                $result = mysqli_query($conn, $search_query);

                                // Fetch and store results
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Add the serial number to the row
                                    $row['serial_number'] = $serialNumber;
                                    $results[] = $row;
                                    $serialNumber++; // Increment the serial number
                                }
                            }
                        }
                        ?>

                        <?php if (!empty($results)) : ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Branch</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>User type</th>
                                        <th>Sex</th> <!-- Add Sex column -->
                                        <!-- Add other columns you want to display -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php foreach ($results as $row) : ?>
                                        <tr>
                                            <td><?php echo $row['serial_number']; ?></td>
                                            <td><?php echo $row['branch_name']; ?></td>
                                            <td><?php echo $row['first_name']; ?></td>
                                            <td><?php echo $row['last_name']; ?></td>
                                            <td><?php echo $row['user_type']; ?></td>
                                            <td><?php echo $row['sex']; ?></td> <!-- Display the sex column -->
                                            <!-- Add other cells for columns you want to display -->
                                            <td>
                                                <a href="staff_profile.php?id=<?php echo $row['username']; ?>" class="btn btn-primary">View</a>
<!--                                                <a href="update_student.php?id=<?php echo $row['username']; ?>" id="admin-content" class="btn btn-success">Update</a>
                                                <a href="remove_member.php" onclick="return confirm('Are you sure you want to delete this student?')" id="content" class="btn btn-danger">Delete</a>-->
                                            </td>
                                        </tr>
    <?php endforeach; ?>
                                </tbody>
                            </table>
<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
