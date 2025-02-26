<?php
require('session.php');
require('connect.php');

$profile_data = []; // Initialize an empty array to avoid "Undefined array key" warnings

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $username = $_GET['id'];

    // Query to fetch staff details based on staff ID
    $profile_query = "SELECT username,branch_name, branch_region,date_assigned , email, first_name , last_name , sex , dob,mobile, address, date_joined, profile_photo_url FROM staff WHERE username = '$username'";

    $profile_result = mysqli_query($conn, $profile_query);

    if (!$profile_result) {
        echo "Error: " . mysqli_error($conn);
    } else {
        $profile_data = mysqli_fetch_assoc($profile_result);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form was submitted and staff ID is set
    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        // Upload profile photo
        if (isset($_FILES['profile_photo'])) {
            $uploadDir = "profile_photos/";
            $uploadPath = $uploadDir . basename($_FILES["profile_photo"]["name"]);

            if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $uploadPath)) {
                // Update profile photo URL in the database
                $profile_photo_url = $uploadPath;
                $update_query = "UPDATE staff SET profile_photo_url = '$profile_photo_url' WHERE username = '$username'";

                if (mysqli_query($conn, $update_query)) {
                    $upload_message = "Profile photo uploaded successfully.";
                    $profile_data['profile_photo_url'] = $profile_photo_url;
                    header('location:branch_search_members.php');
                } else {
                    $upload_message = "Error updating profile photo: " . mysqli_error($conn);
                }
            } else {
                $upload_message = "Error uploading profile photo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="HOUSEOFGRACE" content="HOUSEOFGRACE" /> <!-- website name -->
        <meta property="HOUSEOFGRACE" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="HouseofGrace Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
        <title>Profile</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            .main-page{
                justify-content: center;
                text-align: center;
            }
        </style>
        <!-- ... (rest of your HTML and CSS) ... -->
    </head>
    <body>
        <div class="container">
            <h1>Member Profile</h1>

            <?php if (!empty($profile_data)) : ?> <!-- Check if $profile_data is not empty -->
                <div>
                    <?php if (!empty($profile_data['profile_photo_url'])) : ?>
                        <img src="<?php echo $profile_data['profile_photo_url']; ?>" alt="Profile Photo"
                             style="border-radius: 50%; height: 100px; width: 100px;">
                         <?php else : ?>
                        <p>No profile photo available.</p>
                    <?php endif; ?>
                </div>

                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <div>
                        <label for="profile_photo">Upload Profile Photo:</label>
                        <input type="file" name="profile_photo" accept="image/*">
                    </div>
                    <button type="submit">Upload Photo</button>
                    <?php if (isset($upload_message)) : ?>
                        <p><?php echo $upload_message; ?></p>\
                    <?php endif; ?>
                </form>

                <table>
                    <tr>
                        <th>Login Name</th>
                        <td><?php echo $profile_data['username']; ?></td>
                    </tr>
                    <tr>
                        <th>Branch Name</th>
                        <td><?php echo $profile_data['branch_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Date Assigned to Church</th>
                        <td><?php echo $profile_data['date_assigned']; ?></td>
                    </tr>
                    <tr>
                        <th>District</th>
                        <td><?php echo $profile_data['branch_region']; ?></td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td><?php echo $profile_data['first_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><?php echo $profile_data['last_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Sex</th>
                        <td><?php echo $profile_data['sex']; ?></td>
                    </tr>
                    <tr>
                        <th>Date Of Birth</th>
                        <td><?php echo $profile_data['dob']; ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?php echo $profile_data['mobile']; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo $profile_data['email']; ?></td>
                    </tr>
                    <tr>
                        <th>Resident address</th>
                        <td><?php echo $profile_data['address']; ?></td>
                    </tr>
                    <tr>
                        <th>Date joined</th>
                        <td><?php echo $profile_data['date_joined']; ?></td>
                    </tr>

                    <!-- Add other fields here -->
                </table>
            <?php else : ?>
                <p>No profile data available.</p>
            <?php endif; ?>

            <div><br>
                <a href="admin_main.php"
                   style="background-color: blue; color: #fff; text-decoration: none; border-radius: 20px; padding: 8px; margin-left: 80%;margin-left:0;">Home</a><br>
                <a href="search_member.php"
                   style="background-color: blue; color: #fff; text-decoration: none; border-radius: 20px; padding: 8px; margin-left: 60%;">Back to Staff List</a>
            </div>
        </div>

      
    </body>
</html>


