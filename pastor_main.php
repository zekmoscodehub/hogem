<?php
require('session.php');
require('connect.php');

// Check the user's user_type and display content accordingly
if ($_SESSION['user_type'] === 'Branch_Pastor') {
    header('location:Branch_Pastor.php');
}
if ($_SESSION['user_type'] === 'admin') {
    header('location:admin_main.php');
}
if ($_SESSION['user_type'] === 'Assistant_Pastor') {
    header('location:ass.pastor_main.php');
}
elseif (!$_SESSION['user_type'] === 'Pastor') {
    header('location:login.php');
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
        <meta property="Church Management System" content="House Of Grace Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg">
        <title>Pastor</title>
                 
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/plugins/feather/feather.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="assets/css/admin.css">
    </head>
<style>
    body{
        position:absolute;
    }
            .menu{
    display: none;
}
</style>
    <body>

<!--        <a href="logout.php">Log Out <span class="glyphicon glyphicon-off" aria-hidden="true"></span></a>-->
        <div class="main">

            <div class="menu">
                <input type="checkbox" id="check"/> 
                <label for="check" >
                    <i class="fas fa-bars" id="btn"></i>
                    <i class="fas fa-times" id="cancel"></i>
                </label>

                <div class="sidebar" id="sidebar" style="margin-top:0;">
                    <div class="sidebar-inner ">
                        <div id="sidebar-menu" class="sidebar-menu">
                            <ul>
                                <li class="menu-title">
                                    <a href="pastor_main.php"><span class="dashboard"><i class="fas fa-qrcode"style="margin-right:8px;"></i>Dashboard</span></a>
                                </li>
                                <li>
                                    <!--<a href="branch_search_members.php"><i class="fas fa-church"></i> <span>Members</span> <span-->
                                            <!--class="menu-arrow"></span></a>-->
                                </li>
                                <li class="submenu">

                                    <ul>

                                        <!--<li><a href="branch-services-report.php"><i class="fas fa-calendar-day"></i>Church Service</a></li>-->

                                        <div>
                                            <!--<li><a href="financials.php" class="" style="padding-right:0px;"><i class="fas fa-book" style=margin-right:5px; font-family=sans-serif;></i>Financial</a></li>-->

                                        </div>
<!--                                        <li><a href="register.php"><i class="fas fa-plus"style="color:green;"></i>member</a></li>
                                        <li><a href="remove_member.php"><i class="fas fa-times" style="color:red;"></i>Delete Member</a></li>-->
                                    </ul>
                                </li>

                                <li class="submenu">
                                    <a href="#"><i class="fas fa-use"></i> <span></span> <span
                                            class="menu-arrow"></span></a>
                                    <ul>

                                    </ul>
                                </li>
                                <br>

                                <li class="submenu">
                                    <a href="#"><i class="fas fa-graduatio-cap"></i> <span> </span> <span
                                            class="menu-arrow"></span></a>
                                    <ul>
                                            <!-- <li><a href="#"><i class="fas fa-file"></i>Add Records</a></li> -->

                                    </ul>
                                </li>

                                <li>
                                   
<!--                    <form method="post" action="upload.php" enctype="multipart/form-data">
                        <h5 style="color: blue; margin-top:-70px;">Upload a Service Report (<i style="font-size:13px; color:red;"> on behalf of your Branch Pastor</i> )</h5><hr>

                <label for="branch_name">Branch Name</label>
                <input type="text" id="staff_id"style="width:100%;" name="branch_name" required>

                 <label for="status">Status(is is verified By Head Quarters):</label>
                <select type="text" id="status" name="status" required class="form-control">
                    <option value="No">No</option>
                
                </select>
                 <label for="note_title" style="margin:10px;">Name (Service Date)</label>
                <input type="text" id="note_title" name="note_title"style="width:100%;" required>

                <label for="service_reports">Upload Lesson Note (PDF, Word, JPEG, PNG, GIF, DOCX):</label>
                <input type="file" id="service_reports"style="width:100%;" name="file_upload" accept=".pdf, .jpeg, .jpg, .png, .gif, .docx" required>

                <input type="submit" value="Upload File" style="width:100%;margin: 10px auto;">
                
              
                
            </form> <hr>-->
                                    <!--<a href="financials.php"  class ="btn btn-primary" style="width:auto;">Financial Report</a>-->
                                    <br> <hr>
                                
                                  
                                   
                                </li>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto profile-btn">


        </ul>
        <a href="logout.php" class="btn btn-primary" style=" background: red; border:none;font-weight:900;font-size:16px;">Logout</a>

        <div class="inner-box">

            <div class="page-wrapper" style="margin-top:0;">

                <div class="content container-fluid">
                
                    <div class="row">
                        
                        <div class="welcome"style="margin-left:20px; margin-bottom:10px;"> <?php echo "Welcome, <a href='pastor_main.php'>" . $_SESSION['username'] . "</a>!"; ?></div>
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

                                        <h6 class="text-muted">House Of Grace Church Head Office</h6>

                                        <div class="user-Location about-text" style="color:blue"> <i class="fas fa-map-marker-alt"style="color:green;" > </i> Main Branch<br> Zuarungu, Bolga East .</div>

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
                                                        <a  href="#"><i class="far fa-edit me-1"></i> Edit</a>
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span>Church Searvice Reports</span>
                                                                                                                  </h5>
                                                        
                                                    </div>
                                                    <a href="" class="btn btn-success " type="button" title="Not active for current user" ><i
                                                                class="fe fe-check-verified"></i> View attendance</a><br>
                                                    <a href="" class="btn btn-success" type="button" title="Not active for current user" ><i
                                                                class="fe fe-check-verified"></i> Enter Report</a>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span> Financial Reports</span>
                                                            <!--<a class="edit-link" href="#"><i class="far fa-edit me-1"></i> Edit</a>-->

                                                        </h5>
                                                       
                                                    </div>
                                                    <a href="" class="btn btn-success " type="button" title="Not active for current user"><i
                                                                class="fe fe-check-verified"></i>View Report</a><br>
                                                    <a href="" class="btn btn-success " type="button" title="Not active for current user"><i
                                                                class="fe fe-check-verified"></i>Enter Report</a>
                                                </div>
                                                
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title d-flex justify-content-between">
                                                            <span>Church Members</span>
                                                            <!--<a class="edit-link" href="#"><i class="far fa-edit me-1"></i> Edit</a>-->

                                                        </h5>
                                                        <a href=""class="btn btn-success " type="button" title="Not active for current user"><i
                                                                class="fe fe-check-verified"></i> View Members</a><br><br>
                                                                <a href=""class="btn btn-success " type="button" title="Not active for current user"><i
                                                                class="fe fe-check-verified"></i> Add Member</a>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div style="margin-top:30px;">

                                    <footer>
                                        <p>Copyright &copy 2025 House Of Grace Church - Ghana</p>
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


