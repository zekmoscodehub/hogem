<?php
require('session.php');
require('connect.php');

    

// Check the user's user_type and display content accordingly
if ($_SESSION['user_type'] === 'Branch Pastor') {
    header('location:branch_main.php');
}

if ($_SESSION['user_type'] === 'Assistant Pastor') {
    header('location:ass.pastor_main.php');
}
elseif ($_SESSION['user_type'] === 'Member') {
    header('location:member-main.php');
}

if ($_SESSION['user_type'] === 'Pastor') {
    header('location:pastor_main.php');
}
if (!$_SESSION['user_type'] === 'admin') {
    header('location:index.php');
}
// else{
//     header('location:index.php'); //
// }


 

?>
<?php
// Include your database connection and any other necessary includes
require_once 'connect.php';

// Check if a file was uploaded
if (isset($_FILES['file_upload'])) {
    $file = $_FILES['file_upload'];

    // Validate and sanitize the uploaded file name
    $file_name = mysqli_real_escape_string($conn, $file['name']);

    // Additional data to insert
    $branch_name = isset($_POST['branch_name']) ? mysqli_real_escape_string($conn, $_POST['branch_name']) : null;
    $note_title = isset($_POST['note_title']) ? mysqli_real_escape_string($conn, $_POST['note_title']) : null;
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : null;

    // Specify the directory where you want to store uploaded files
    $upload_directory = "service_reports/"; // Create this directory if it doesn't exist
    $file_path = './service_reports/' . $file_name;

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        // File upload successful, now update the database
        $query = "INSERT INTO service_reports (branch_name, note_title, file_name, status, file_path) VALUES ('$branch_name', '$note_title', '$file_name', '$status', '$file_path')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // File information successfully added to the database
            $successMessage = "File  successfully uploaded";
        } else {
            $error = 'Error updating the database: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Error moving the uploaded file to the server.';
    }
} else {
    $error = 'No file was uploaded.';
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
    <meta property="Church Management System" content="House Of Grace App" /> <!-- title shown in the actual shared post -->
    <link rel="shortcut icon" href="assets/img/logo1.jpg"> <!-- title shown in the actual shared post -->
    <title>Admin Home</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/feather/feather.css">
    <!--<link rel="stylesheet" href="assets/css/bootstrap.min.css"/>-->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        /* General Styles */
        body {
            font-family:sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            /* height: vmin; */
            position: absolute;
        }

        .main {
            display: flex;
            justify-content: space-around;
        }

        /* Sidebar Styles */
        .menu {
            position: absolute;
            width: 250px;
            /* background-color: #30336b; */
            color: #fff;
            height: 100vh;
            padding-top: 50px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar ul li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }
  .menu{
    display: none;
}
        /* Content Styles */
        .container {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
        }

        .container h1 {
            margin-bottom: 20px;
        }

        .btn-success {
            margin-bottom: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Form Styles */
        .input-group {
            margin-bottom: 20px;
        }

        /* Alert Styles */
        .alert {
            margin-bottom: 20px;
        }

        /* Report Styles */
        .report {
            padding: 20px;
            background-color: #fff;
            margin-top: 20px;
        }

        .report table {
            margin-top: 10px;
        }

        .report th, .report td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .report th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .btn-danger {
            margin-left: 10px;
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            body{
                text-align: center;
                justify-content: center;
                width: 100vmin;
            }
            .main {
                flex-direction: column;
            }

            .menu {
                width: 100%;
                height: auto;
            }
            .container {
                padding: 10px;
            }
        }
        .sidebar-menu{
            margin-top: -30px;
           
        }
      
    </style>
</head>

<body>
<div class="main">
    
    <div class="menu">
                <input type="checkbox" id="check"/> 
                <label for="check" >
                    <i class="fas fa-bars menu-bars" id="btn"></i>
                    <i class="fas fa-times" id="cancel"></i>
                </label>

                <div class="sidebar" id="sidebar" style="margin-top:0;height: auto;">
                    <div>
                        <div id="sidebar-menu" class="sidebar-menu">
                            
                            <ul>

                                <li class="submenu">

                                    <a href="admin_main.php"><i class="fas fa-church"></i> <span class="dashboard">Dashboard</span><span
                                            class="menu-arrow"></span></a>
                                    <ul>

                                        <li><a href="search_member.php"><i class="fas fa-cross"></i>Churches</a></li>
                                        <li><a href="#"><i class="fas fa-calendar-day"></i>Reports <i class="fas fa-arrow-circle-down"style="font-size:12px;"></i> </a>
                                            <ul>

                                                
                                                <li><a href="service.php"style="font-family:sans-serif;"><i class="fas fa-anchor"></i>Service Report<span ></span></a></li>
                                                <li><a href="financials.php"style="font-family:sans-serif;"><i class="fas fa-anchor"></i>Finances<span ></span></a></li>
                                                <li><a href="uploads.php" class="" style="margin-right:5px; font-family:sans-serif;"><i class="fas fa-file"></i>Upload</a></li>

                                            </ul>
                                        </li>

                                </li>
                                <li class="submenu">
                                    <a href="#"><i class="fas fa-qrcode"style="margin-right:8px;"></i> <span>Resources </span> <i class="fas fa-arrow-circle-down"style="font-size:12px;"></i> <span
                                            class="menu-arrow"></span></a>
                                    <ul>

                                        <li><a href="search_member.php"title="View Members"><i class="fas fa-users"></i> Members</a></li>

                                        <li><a href="register.php"><i class="fas fa-plus"style="color:green;"></i>Add Branch</a></li>
                                        <li><a href="register.php"title="Add Church Worker or Member"><i class="fas fa-plus"style="color:green;"></i>Member</a></li>
                                        <li><a href="remove_member.php"title="Delete Member"><i class="fas fa-times" style="color:red;"></i> Member</a></li>
                                    </ul>
                                </li>

                            </ul>

                            </li>
                            <div>
<?php
// Include your database connection and any other necessary includes
require_once 'connect.php';

// Check if a file was uploaded
if (isset($_FILES['file_upload'])) {
    $file = $_FILES['file_upload'];

    // Validate and sanitize the uploaded file name
    $file_name = mysqli_real_escape_string($conn, $file['name']);

    // Additional data to insert
    $branch_name = isset($_POST['branch_name']) ? mysqli_real_escape_string($conn, $_POST['branch_name']) : null;
    $note_title = isset($_POST['note_title']) ? mysqli_real_escape_string($conn, $_POST['note_title']) : null;
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : null;

    // Specify the directory where you want to store uploaded files
    $upload_directory = "service_reports/"; // Create this directory if it doesn't exist
    $file_path = './service_reports/' . $file_name;

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        // File upload successful, now update the database
        $query = "INSERT INTO service_reports (branch_name, note_title, file_name, status, file_path) VALUES ('$branch_name', '$note_title', '$file_name', '$status', '$file_path')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // File information successfully added to the database
            $successMessage = "File  successfully uploaded";
        } else {
            $error = 'Error updating the database: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Error moving the uploaded file to the server.';
    }
} else {
    $error = 'No file was uploaded.';
}

// If there was an error, handle it appropriately (e.g., display an error message).
?>
                                <!DOCTYPE html>
                                <html lang="en">

                                    <head>
                                        <meta charset="utf-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
                                        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
                                        <meta property="HOUSE OF GRACE" content="HOUSE OF GRACE" /> <!-- website name -->
                                        <meta property="HOUSE OF GRACE" content="Church in Ghana" /> <!-- website link -->
                                        <meta property="Church Management System" content="House Of Grace App" /> <!-- title shown in the actual shared post -->
                                        <title> Admin Home</title>

                                        <!-- <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
                                        <link rel="stylesheet" href="assets/plugins/feather/feather.css">
                                        <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
                                        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
                                        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css"> -->
                                        <link rel="stylesheet" href="assets/css/admin.css">
                                    </head>
                                    <style>

                                        .logout:hover{
                                            background: red;

                                        }

                                    </style>
                                    <div class="form-group"style="justify-content: center;margin: 10px auto;">
<?php
// Display validation error, if any

if (!empty($successMessage)) {
    echo '<p class="text-green"style="text-align:center; justify-content:center;font-size:16px;margin-left:100px;">' . $successMessage . '</p>';
}
?>
                                        <hr>
                                      
                                        <a href="admin-services-report.php"  class ="btn btn-primary" style="width:auto;">View Service Reports</a>
                                        <br>
                                    </div>
                            </div>
                            <div>
                                <a href="bui.php"><i class="far fa-edit me-1"></i></a>
</div>
                            <hr>
      
                            <hr>
                            <div class="col-auto profile-btn">

                                <a href="logout.php" class="btn btn-secondary logout" style="width:50%; border:none;font-weight:900;font-size:14px;margin-left:-30px;">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>

                </ul>
            </div> 
    
            <div class="inner-box inner-box2">

                <div class="page-wrapper" style="margin-top:0;margin-left:30px;">

                    <div class="content container-fluid">

                        <div class="row"style="justify-content:space-around;">

                            <div class="welcome"style="margin-left:20px; margin-bottom:10px;"><?php echo "Welcome, <a href='admin_main.php'>" . $_SESSION['username'] . "</a>!"; ?></div>
                            <div class="col-md-12 sm-4">
                                <div class="profile-header" style="justify-content:space-around;">
                                    <div class="row align-items-center">
                                        <div class="col-auto profile-image">
                                            <a href="#">
<?php
// upload directory for profile photos
$uploadDir = "profile_photos/";

// Handle profile photo upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profile_photo"])) {
    $user_id = $_SESSION['user_id'];
    $uploadPath = $uploadDir . basename($_FILES["profile_photo"]["name"]);

    if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $uploadPath)) {
        // Update the user's profile photo URL in the database
        $profile_photo_url = $uploadPath;
        $sql = "UPDATE staff SET profile_photo_url = '$profile_photo_url' WHERE id = $user_id";
        if ($conn->query($sql) === TRUE) {
            $message = "Profile photo uploaded successfully.";
        } else {
            $message = "Error updating profile photo: " . $conn->error;
        }
    } else {
        $message = "Error uploading profile photo.";
    }
}

// Retrieve user information from the database

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM staff WHERE id = $user_id";
$result = $conn->query($sql);
$user_data = $result->fetch_assoc();

$conn->close();
?>
                                                <?php if (isset($message)) {
                                                    echo "<p>$message</p>";
                                                } ?>

                                                <?php if ($user_data['profile_photo_url']) : ?>
                                                    <img src="<?php echo $user_data['profile_photo_url']; ?>" alt="Profile Photo"
                                                         style="border-radius:50%; height:100px; width:100px;">
                                                <?php endif; ?>

                                                <form action="admin_main.php" method="POST" enctype="multipart/form-data" style="margin:10px";>
                                                    <input type="file" name="profile_photo" accept="image/*"
                                                           style="margin-top:10px;">
                                                    <input type="submit" value="Upload Photo">
                                                </form>
                                            </a>
                                            
                                        </div>

                                        <div class="col ms-6 md-6 profile-user-info"style="margin-left:200px;">

                                            <h6 class="text-muted">Head Office</h6>
                                                
                                            <div class="user-Location about-text" style="color:blue"> <i class="fas fa-map-marker-alt"style="color:green;" > </i> House Of Grace<br> Zuarungu - Bolga East.</div>

                                        </div>
                                        <div class="col-auto profile-btn">

<a href="logout.php" class="btn btn-primary" style="width:100%; background: red; border:none;font-weight:900;font-size:16px;">Logout</a>
</div>
                                    </div>
                                </div>
                                <div class="profile-menu">
                                    <ul class="nav nav-tabs nav-tabs-solid">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">About</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="change_password.php" class=" btn btn-success"style="padding:5px;margin:0 5px;"  >Change Password</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="events_news.php" class=" btn btn-secondary"style="padding:5px;margin:3px 10px;"  >Events and news</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="bui.php" class=" btn  bg-info"style="padding:5px;margin:3px 15px;"  >Approvals</a>
                                        </li>
                                    
                                    </ul>
                                </div>
                                <div class="tab-content profile-tab-cont inner-box2">

                                    <div class="tab-pane fade show active" id="per_details_tab">

                                        <div class="row"style="min-width:100%;margin-left:80px;text-align:center;">
                                            <div class="col-lg-6 "style="width:750px;">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="h2" style="text-align:center;color:dodgerblue;">Personal Information
                                                        </div>
                                                        <h5 class="card-title d-flex justify-content-between">

                                                            <span>Personal Details</span>
                                                            <!--<a class="edit-link" data-bs-toggle="modal" href="#edit_personal_details"><i class="far fa-edit me-1"></i>Edit</a>-->

                                                        </h5>
<?php
require 'connect.php';

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];

    // Query to fetch user data from the staff table based on username
    $query = "SELECT title, first_name, last_name, dob, email, user_type,branch_name,branch_region, mobile, address FROM staff WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $dob = $row['dob'];
        $email = $row['email'];
        $user_type = $row['user_type'];
        $branch_name = $row['branch_name'];
        $branch_region = $row['branch_region'];
        $mobile = $row['mobile'];
        $address = $row['address'];

        // Display the fetched data in the desired format
        echo "<div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Name: </p>
                <p class='col-sm-9'>$title $first_name $last_name</p>
              </div>
              <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Date of Birth: </p>
                <p class='col-sm-9'>$dob</p>
              </div>
              <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Email: </p>
                <p class='col-sm-9'>$email</p>
              </div>
              <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Role: </p>
                <p class='col-sm-9'>$user_type</p>
              </div>
               <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Branch Name: </p>
                <p class='col-sm-9'>$branch_name</p>
              </div>
               <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Branch Region : </p>
                <p class='col-sm-9'>$branch_region</p>
              </div>
              <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Mobile: </p>
                <p class='col-sm-9'>$mobile</p>
              </div>
              <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Address/Location: </p>
                <p class='col-sm-9'>$address</p>
              </div> ";
    } else {
        echo "No user data found.";
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    echo "User not logged in.";
}

// Close the database connection
mysqli_close($conn); // Make sure this line is only executed once, after fetching and displaying data
?>

                                                        <p class="col-sm-9 mb-0"><?php
                                                        if (isset($_SESSION['username'])) {
                                                            // Include your database connection code if needed
                                                            // Display user's details here
                                                            // Include the code to display the location details
//    include 'display_location.php';
                                                        } else {
                                                            echo "User not logged in.";
                                                        }
                                                        ?><br>

                                                    </div>
                                                </div>
           

                                            </div>

                                            <div class="col-lg-3">

                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span>Account Status</span>
                                                            <!--<a class="edit-link" href="#"><i class="far fa-edit me-1"></i> Edit</a>-->

                                                        </h5>
                                                        <button class="btn btn-success" type="button" title="Your account is ACTIVE"><i
                                                        class="fas fa-check-verified"></i> Active</button>
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span class="h5"> Church Membership</span>                                               
                                                        </h5>
                                                        <div class="rep">

                                                        </div>
                                                        <a href="search_member.php" class="btn btn-success">
                                                                <i class="fas fa-users" style="padding:10px;"></i>Search Members</a></a>
                                                                <a href="register.php" class="btn btn-success">
                                                                <i class="fas fa-user" style="padding:10px;" ></i>  Add Member</a></a>
                                                                <a href="register-admin.php" class="btn btn-success">
                                                                <i class="fas fa-anchor" style="padding:10px;" ></i>Add Admin</a></a>
                                                                <a href="remove_member.php" class="btn btn-danger">
                                                                <i class="fas fa-tools" style="padding:10px;" ></i>  Delete a Member</a></a>
                                                        <!-- <a href="member-view.php" class="btn btn-success">
                                                                <i class="fe fe-check-verified"></i>View by Branch</a>
                                                                 -->
                                                                
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span><i class="fas fa-church"style="padding:10px;"></i>Church Searvice Reports</span>
                                                                                                                  </h5>
                                                        
                                                    </div>
                                                    <a href="service-attendance-report.php" class="btn btn-success" type="button"><i
                                                                ></i> View Attendance</a>
                                                    <a href="service.php" class="btn btn-success" type="button"><i
                                                    class="fas fa-pen"style="padding:10px;" ></i> Enter Report</a>
                                                    <a href="admin-services-report.php" class="btn btn-success" type="button"><i
                                                    class="fas fa-upload"style="padding:10px;" ></i> Upload / View Uploads</a>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span> Financial Reports</span>
                                                            <!--<a class="edit-link" href="#"><i class="far fa-edit me-1"></i> Edit</a>-->

                                                        </h5>
                                                       
                                                    </div>
                                                    <a href="view-financials.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i>View Financial Report</a>
                                                    <a href="financials.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i>Enter Financial Report</a>
                                                </div>
                                               
                                               <a><a href="bui.php"><i class="far fa-edit me-1"></i>My Approvals</a></>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <br>

                                <div style="margin-top:30px;text-align:center;">

                                    <footer>
                                        <p>&copy 2024 House Of Grace. All Rights Reserved.</p>
                                    </footer>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/feather.min.js"></script>
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/js/script.js"></script>

    </body>

</html>


