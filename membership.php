<?php
// Assuming you have already established a database connection
require('session.php');
require('connect.php');

// Check if the region parameter is set
if (isset($_GET['region'])) {
    $region = $_GET['region'];

    // Prepare the query to fetch members for the selected region
    $search_query = "SELECT username, user_type, branch_region, branch_name, address, mobile, email, date_joined FROM staff WHERE branch_region = '$region'";

    // Perform the query
    $result = mysqli_query($conn, $search_query);
}
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
        <title>Membership for <?php echo $region; ?></title>
        <!-- Include any necessary stylesheets or CSS -->
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/plugins/feather/feather.css">
        <link rel="stylesheet" href="assets/plugins/icons/flags/flags.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        
    </head>
    <style>
        body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                height: 100vh;
                flex-wrap: wrap;
                min-width: 100%;
                display:flex;


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
            .main{ min-width:90%;
                   align-items: center;
            }
            table {
             min-width: 80%;
                border-collapse: collapse;
                margin-top: 20px;
               margin-left:20%;
                
            }

            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f2f2f2;
            }

    .home{
        margin-bottom:50px;
        margin-top: 40px;
        position: relative;
       max-width:115px;
    </style>
    <body>
        <div class="main">
            <div>
        <h1>Members in the <?php echo $region; ?> Branch</h1>
         <a class="home" href="admin_main.php" style="background-color: blue; color: #fff; text-decoration: none; border-radius: 20px; padding: 8px; margin-left: 50px; margin-top:200px;">Back to Home</a>
            </div>
        <table>
            <thead>
                <tr>
                   
                    <th>Username</th>
                    <th>Role</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Date Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($result) && mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['user_type']; ?></td>
                            <td><?php echo $row['branch_name']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['mobile']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['date_joined']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No members found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
       
        <!-- Include any necessary scripts -->
        
    </body>
</html>
