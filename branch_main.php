<?php
// session_start();
require ('session.php');
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$username = $_SESSION['username'];

if ($_SESSION['user_type'] === 'admin') {
    header('location:admin_main.php');
}

if ($_SESSION['user_type'] === 'Pastor') {
    header('location:ass.pastor_main.php');
}
if ($_SESSION['user_type'] === 'Worker') {
    header('location:ass.pastor_main.php');
}
elseif ($_SESSION['user_type'] === 'Member') {
    header('location:member-main.php');
}
if (!$_SESSION['user_type'] === 'Assistant_Pastor') {
    header('location:ass.pastor_main.php');
}
if ($_SESSION['user_type'] === 'admin') {
    header('location:admin_main.php');
}

if ($_SESSION['user_type'] === 'Pastor') {
    header('location:ass.pastor_main.php');
}
if ($_SESSION['user_type'] === 'Worker') {
    header('location:ass.pastor_main.php');
}
elseif ($_SESSION['user_type'] === 'Member') {
    header('location:member-main.php');
}
if ($_SESSION['user_type'] === 'Assistant_Pastor') {
    header('location:ass.pastor_main.php');

}
if (!$_SESSION['user_type'] === 'Branch_Pastor') {
    header('location:index.php');
}

// Fetch user profile data securely
$query = "SELECT title, first_name, last_name, dob, email, user_type, branch_name, branch_region, mobile, address 
          FROM staff WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    extract(array_map('htmlspecialchars', $row));
    $_SESSION['branch_region'] = $branch_region; // Ensure session stores region
} else {
    die("No user data found.");
}

// Ensure branch_region is set in session
if (!isset($_SESSION['branch_region']) || empty($_SESSION['branch_region'])) {
    die("Access denied: No branch region assigned.");
}

$user_branch_region = $_SESSION['branch_region'];
$user_branch_name = $_SESSION['branch_name'];
// Check the user's user_type and display content accordingly
// Check the user's user_type and display content accordingly

 

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
    <meta property="Church Management System" content="House Of Grace Evangelical Church App" /> <!-- title shown in the actual shared post -->
    <title>Pastors Page</title>
    <link rel="shortcut icon" href="assets/img/i33--1x-1.png"><link
         rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/feather/feather.css">
    <!--<link rel="stylesheet" href="assets/css/bootstrap.min.css"/>-->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        body {
            font-family:sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            /* display: block; */
            margin: 10px 20px;
            /* height: vmin; */
            /* position: absolute; */
        }

        /* .main {
            /* display: flex; */
            /* justify-content: space-around; */
        } */

        /* Sidebar Styles */
        .menu {
            /* position: absolute; */
            /* width: 250px; */
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

 

<!--        <a href="logout.php">Log Out <span class="glyphicon glyphicon-off" aria-hidden="true"></span></a>-->
    <div class="main">
       
        <div class="menu">

</div>
</ul>
        <div class="inner-box">
            

    <div class="page-wrapper" style="margin-top:0;">

        <div class="content container-fluid">

            <div class="row">
                <div class="welcome"style="margin-left:20px; margin-bottom:10px;"> <?php  echo "Welcome, <a href='branch_main.php'>".$_SESSION['username']."</a>!";?></div>
                <div class="col-md-12">
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-auto profile-image">
                                <a href="#">
                                    <?php  
    // Define the upload directory for profile photos
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
                                    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

                                    <?php if ($user_data['profile_photo_url']) : ?>
                                    <img src="<?php echo $user_data['profile_photo_url']; ?>" alt="Profile Photo"
                                        style="border-radius:50%; height:100px; width:100px;">
                                    <?php endif; ?>

                                    <form action="admin_main.php" method="POST" enctype="multipart/form-data">
                                        <input type="file" name="profile_photo" accept="image/*"
                                            style="margin-top:10px;">
                                        <input type="submit" value="Upload Photo">
                                    </form>
                                </a>
                            </div>

                            <div class="col ms-md-n2 profile-user-info"style="margin-left:100px">

                            <h6 class="text-muted">Head Office</h6>

<div class="user-Location about-text" style="color:blue"> <i class="fas fa-map-marker-alt"style="color:green;" > </i> House Of Grace Evangelical Church  <br> Zuarungu, Bolga East .</div>

</div>
<div class="col-auto profile-btn">

<a href="logout.php" class="btn btn-primary" style="width:100%; background: red; border:none;font-weight:900;font-size:16px;">Logout</a>
</div>
                    <div class="profile-menu">
                        <ul class="nav nav-tabs nav-tabs-solid">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">About</a>
                            </li>
                            <li class="nav-item">
                                <a href="change_password.php" class=" btn btn-success"style="padding:5px;margin:0 5px;"  >Change Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content profile-tab-cont">

                        <div class="tab-pane fade show active" id="per_details_tab">

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="h2" style="padding-left:40%;color:dodgerblue;">Personal Information
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
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Name:</p>
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
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>User Type:</p>
                <p class='col-sm-9'>$user_type</p>
              </div>
               <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Branch Name:</p>
                <p class='col-sm-9'>$branch_name</p>
              </div>
               <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Region:</p>
                <p class='col-sm-9'>$branch_region</p>
              </div>
              <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Mobile:</p>
                <p class='col-sm-9'>$mobile</p>
              </div>
              <div class='row'>
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Address/Location:</p>
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
                                           
                                                <p class="col-sm-9 mb-0"><?php  if (isset($_SESSION['username'])) {

} else {
    echo "User not logged in.";
}
 ?><br>

                                            </div>
                                        </div>
                                    </div>
                                
                        
                                <div class="col-lg-4">

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title d-flex justify-content-between">
                                                <span>Account Status</span>
                                                <!--<a class="edit-link" href="#"><i class="far fa-edit me-1"></i> Edit</a>-->

                                            </h5>
                                            <button class="btn btn-success" type="button"><i
                                                    class="fe fe-check-verified"></i> Active</button>
                                        </div>
                                    </div>

                                    <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span>Church Searvice Reports</span>
                                                                                                                  </h5>
                                                        
                                                    </div>
                                                    <a href="branch-service-attendance.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i> View attendance</a>
                                                    <a href="branch-service.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i> Enter / View Report</a>
                                                                <a href="branch-services-report.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i> View / Upload Report</a>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span> Financial Reports</span>
                                                            <!--<a class="edit-link" href="#"><i class="far fa-edit me-1"></i> Edit</a>-->

                                                        </h5>
                                                       
                                                    </div>
                                                    <a href="branch-view-financials.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i>View Report</a>
                                                    <a href="branch-financials.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i>Enter Report</a>
                                                </div>
                                                
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span>Church Members</span>
                                                            <!--<a class="edit-link" href="#"><i class="far fa-edit me-1"></i> Edit</a>-->

                                                        </h5>
                                                        <a href="branch_search.php"class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i> View Members</a>
                                                                <a href="branch-register.php"class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i> Add Member</a>
                                                    </div>
                                                </div>
                                </div>
                            </div>

                        </div>
                    </div>

           

                               <div style="margin-top:30px;">

                                    <footer>
                                        <p>Copyright &copy 2025 House Of Grace Evangelical Church - Ghana</p>
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

