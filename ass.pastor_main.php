<?php
require('session.php');
require('connect.php');
require('login_function.php');

// Check the user's user_type and display content accordingly

// ?>
<?php
// Include your database connection and any other necessary includes


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
    <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
    <title>Pastor's Home</title>
  <link rel="shortcut icon" href="assets/img/i33--1x-1.png"><link>
  <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/feather/feather.css">
    <!--<link rel="stylesheet" href="assets/css/bootstrap.min.css"/>-->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <!-- <link rel="stylesheet" href="assets/css/admin.css"> -->
    <style>
        /* General Styles */
        body {
            body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.wrapper {
    display: flex;
    flex-direction: row;
}

.sidebar {
    width: 250px;
    background: #333;
    color: #fff;
    height: 100vh;
    padding: 20px;
    position: fixed;
    transition: all 0.3s;
}

.sidebar a {
    display: block;
    color: #ddd;
    text-decoration: none;
    padding: 10px;
    transition: 0.3s;
}

.sidebar a:hover {
    background: #575757;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    flex-grow: 1;
    transition: margin-left 0.3s;
}

.navbar {
    background: #444;
    padding: 15px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar .menu-toggle {
    display: none;
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
}
a{
    margin:0.5rem;
}
.card {
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

.btn {
    background: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.btn:hover {
    background: #0056b3;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.table th {
    background: #007bff;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 200px;
        position: absolute;
        left: -200px;
    }
    .main-content {
        margin-left: 0;
    }
    .navbar .menu-toggle {
        display: block;
    }
}

.sidebar.active {
    left: 0;
}

.main-content.active {
    margin-left: 200px;
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
                        <div class="welcome"style="margin-left:20px; margin-bottom:10px;"> <?php echo "Welcome, <a href='ass.pastor_main.php'>" . $_SESSION['username'] . "</a>!"; ?></div>
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
                                            <?php if (isset($message)) {
                                                echo "<p>$message</p>";
                                            } ?>

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
                <p class='col-sm-3 text-muted text-sm-end mb-0 mb-sm-3'>Role:</p>
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

                                                        <p class="col-sm-9 mb-0"><?php
                                                        if (isset($_SESSION['username'])) {
                                                            
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
                                                                class="fe fe-check-verified"></i> Enter Report</a>
                                                                <a href="branch-services-report.php" class="btn btn-success" type="button"><i
                                                                class="fe fe-check-verified"></i> Upload Report</a>
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

                          
                                <div style="margin-top:30px;text-align:center; margin-right:100px;">

                                    <footer>
                                        <p>&copy 2024 House Of Grace Evangelical Church. All Rights Reserved.</p>
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


