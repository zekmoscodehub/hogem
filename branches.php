<?php
require 'session.php';
require 'connect.php';

if (!$_SESSION['user_type'] === 'Branch_Pastor') {
    header('location:branch_main.php');
}
if (!$_SESSION['user_type'] === 'Admin') {
    header('location:login.php');
}
if (!$_SESSION['user_type'] === 'Pastor') {
    header('location:login.php');
    if ($_SESSION['user_type'] === 'Assistant_Pastor') {
   header("Location: worker-main.php");
}

}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_staff'])) {
        $username = $_POST['username'];

        // Validate and sanitize the input
        if (ctype_digit($username)) {
            // Perform the deletion query
            $delete_query = "DELETE FROM staff WHERE username = $username";
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
$search_query = "SELECT * FROM staff WHERE user_type = 'Branch_Pastor' ";

$result = mysqli_query($conn, $search_query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="HOUSE OF GRACE" content="HOUSE OF GRACE" /> <!-- website name -->
        <meta property="HOUSE OF GRACE" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="House Of Grace Evangelical Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
        <title>branches</title>

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
                justify-content: center;
                text-align: center;
            }

            .container {
                max-width: 90%;
                margin: 0 auto;
                padding: 5px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                margin-bottom: 20px;
            }

            table {
                max-width: 90%;
                border-collapse: collapse;
                margin-top: 20px;
                text-align: center;
                justify-content: center;
                margin: 10px auto;

            }

            th, td {
                padding: 5px;
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
            .logo{
                margin:0 auto;
            }
            @media secreen and (max-width:699px){
                body{
                    color:coral;
                    width: 100vmin;
                    margin: 20px;
                }
            }
        </style>
    </head>
    <div class="members">
        
        <?php

require 'connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['branch_region'])) {
        $selected_region = $_POST['branch_region'];

        // Prepare the query to fetch staff details for the selected region
        if ($selected_region == 'all_members') {
            $search_query = "SELECT username,user_type,branch_region, branch_name, address,mobile,email, date_joined FROM staff";
        } else {
            $branch_name = "";
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="HOUSE OF GRACE" content="HOUSE OF GRACE" /> <!-- website name -->
        <meta property="HOUSE OF GRACE" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="House Of Grace Evangelical Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
        <title>Branch Members</title>
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
                justify-content: center;
                text-align: center;
            }

            .container {
                max-width: 90%;
                margin: 0 auto;
                padding: 5px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                margin-bottom: 20px;
            }

            table {
                max-width: 90%;
                border-collapse: collapse;
                margin-top: 20px;
                text-align: center;
                justify-content: center;
                margin: 10px auto;

            }

            th, td {
                padding: 5px;
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

            .logo {
                margin: 0 auto;
            }

            @media screen and (max-width: 699px) {
                body {
                    color: coral;
                    width: 100vmin;
                    margin: 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="form-box">
            <div class="container" style="margin-top: 20px;">
              

                <h1>House Of Grace Evangelical Church Branches </h1>
                <img src="assets/img/logo1.png" class="logo" alt="alt"/>
                <form method="post" action="branches.php">
                    <select name="branch_region" id="branch_region" class="form-control" style="width: 50%; margin: 10px auto;">
                        <option value="all_members">View All Branches</option>
                        <option value="Accra">Greater Accra</option>
                        <option value="Tamale">Northern</option>
                        <option value="Upper East">Bolgatanga</option>
                        <option value="Westhern">Westhern</option>
                        <option value="Kumasi">Ashanti  </option>
                        <option value="Bolgatanga">Upper East</option>
                        <option value="Easten">Ho</option>
                        <option value="Upper East">Easten</option>
                        <option value="Savana">Savanna</option>
                        <!-- Add other class options here -->
                    </select>
                    <button type="submit" class="btn btn-primary" style="width: 50%; margin: 10px 25%">Search</button>
                </form>
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger" role="alert" style="margin-top: 10px;"><?php echo $error; ?></div>
                <?php endif; ?>
                <span>
                    <?php if (isset($result)) : ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Region</th>
                                    <th>Branch Name</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Pastor</th>
                                    <th>Mobile</th>
<!--                                    <th>Email</th>-->
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><?php echo $row['branch_region']; ?></td>
                                        <td><?php echo $row['branch_name']; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><?php echo  "Branch Pastor"; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['mobile']; ?></td>
<!--                                        <td><?php echo $row['email']; ?></td>-->
                                        <td>
                                            <!-- Membership button -->
                                            <a href="branches.php?region=<?php echo urlencode($row['branch_region']); ?>" class="btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </span>
              
            </div>
        </div>

   
    </body>
</html>

     
      
               
                <div style="margin-left: 80px;"><br>

                    <li style="list-style:none;"><a href="branch-register.php"><i class="fas fa-plus"style="margin-right:10px;color:green;"></i>Add Branch</a>
                    <a href="admin_main.php" style="background-color: blue; color: #fff; text-decoration: none; border-radius: 20px; padding: 8px; margin-left: 35%;margin-top:0px;">Back to Home</a>
                    </li>

                    
                </div>
            </div>
        </div>
    </body>
</html>


