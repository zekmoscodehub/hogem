<?php
// Database credentials

session_start();
require_once 'connect.php';

function arePasswordsMatching($password, $confirmPassword) {
    return $password === $confirmPassword;
}

function clean($data) {
    global $conn;
    // Sanitize the data using mysqli_real_escape_string or any other method you prefer
    return mysqli_real_escape_string($conn, $data);
}

// Initialize variables to hold error messages
$usernameError = $passwordError = $confirmPasswordError = $passwordErrorR = "";
$successMessage = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['cpassword'];
    $user_type = $_POST['user_type'];
    $department = $_POST['department'];
    $branch_region = clean($_POST['branch_region']);
    $branch_name = clean($_POST['branch_name']);
    $title = clean($_POST['title']);
    $first_name = clean($_POST['first_name']);
    $last_name = clean($_POST['last_name']);
    $date_assigned = clean($_POST['date_assigned']);
    $sex = clean($_POST['sex']);
    $dob = clean($_POST['dob']);
    $mobile = clean($_POST['mobile']);
    $address = clean($_POST['address']);
    // Validate and sanitize user inputs (you should do further validation)
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    // Check if passwords match
    if (!arePasswordsMatching($password, $confirmPassword)) {
        // Passwords don't match, set an error message
        $passwordErrorR = "The passwords did not match. Please try again.";
    } else {
        // Check if the username already exists in the database
        $check_username_query = "SELECT * FROM staff WHERE username = '$username' && branch_name = '$branch_name' && dob = '$dob' && first_name= '$first_name'";
        $result = mysqli_query($conn, $check_username_query);

        if (mysqli_num_rows($result) > 0) {
            // Username already exists, set an error message
            $usernameError = "Branch name or user already exist.";
        } else {
            // Insert the new user into the database
            $insert_query = "INSERT INTO staff (username,email, password, user_type,department, branch_region, branch_name,title, first_name, last_name, date_assigned, sex, dob, mobile,address, date_joined) VALUES ('$username','$email', '$password', '$user_type','$department','$branch_region', '$branch_name','$title', '$first_name', '$last_name', '$date_assigned', '$sex', '$dob', '$mobile','$address', NOW())";
            if (mysqli_query($conn, $insert_query)) {

                // echo 'Registration successful!, Please login to continue <a href="login.php">Login</a>';
                $successMessage = " Registered successfully!, <br>Please  <a href='admin_main.php'>Click here</a> to go back</p>.";
//                header('Location:success.php');
            } else {
                echo "Error: " . mysqli_error($conn);
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
        <meta property="HOUSE OF GRACE" content="HOUSE OF GRACE" /> <!-- website name -->
        <meta property="HOUSE OF GRACE" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="House Of Grace Evangelical Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
        <title>Member Registration</title>


        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

        <link rel="stylesheet" href="assets/plugins/feather/feather.css">

        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <style>

        .main-wrapper{
            margin:10px auto;
            color:green;
        }
        .form-group{
            margin:10px 10px;
            max-width: 100vmin;
            min-width: 30vmin;
        }
        .msg{
            margin:10px 80px;
        }
    
    img.logo{
    border-radius: 50%;
    width: 120px;
    background: transparent ;
    margin-top: 40px;
    margin-top:20px
}
</style>

    
    <body>
        <div class="main-wrapper login-body">
            <div class="login-wrapper">
                <div class="container">
                    <div class="loginbox">

                        <div class="login-righ">
                            <div class="login-right-wrap">
                                <div class="msg">
<?php
// Display validation error, if any
if (!empty($usernameError)) {
    echo '<p class="text-danger"style="text-align:center; justify-content:center;font-size:16px;margin-left:100px;">' . $usernameError . '</p>';
}
if (!empty($passwordErrorR)) {
    echo '<p class="text-danger" style="text-align:center; justify-content:center;font-size:16px;margin-left:100px;">' . $passwordErrorR . '</p>';
} else if (!empty($successMessage)) {
    echo '<p class="text-green"style="text-align:center; justify-content:center;font-size:16px;margin-left:100px;">' . $successMessage . '</p>';
}
?>
                                </div>
                                <h1 style="padding-left:50px; color:blue; font-family:sans-serif;"><img class="logo"src="assets/img/logo1.png" alt="logo"style="text-align:center;"/> Member Registration</h1>
                                <p class="account-subtitle"style="padding-left:80px; padding-bottom:-50px">Enter details to Add/Create an account</p>

                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" style="padding-left:80px;">
                                    <div class="form-group">
                                        <lable><span class="login-danger">*</span>User</lable>
                                        <input class="form-control" type="text" name="username"placeholder="Enter a login name">
                                        <!-- <span class="profile-views"><i class="fas fa-user-circle"></i></span> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Email <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="email">
                                        <!-- <span class="profile-views"><i class="fas fa-envelope"></i></span> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Password <span class="login-danger">*</span></label>
                                        <input class="form-control pass-input" type="password"name="password">
                                        <!-- <span class="profile-views feather-eye toggle-password"></span> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm password <span class="login-danger">*</span></label>
                                        <input class="form-control pass-confirm" type="password"name="cpassword">
                                        <!-- <span class="profile-views feather-eye reg-toggle-password"></span> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Role<span class="login-danger">*</span></label>
                                        <select class="form-control" name="user_type" required>
                                           <option value="" disabled selected >Member Type</option>
                                            <option value="Member">Church Member</option>
                                            <option value="Worker">Department Head / Church worker</option>
                                            <option value="Pastor">Pastor</option> 
                                            <option value="Branch Pastor"disabled>Branch Pastor</option>
                                     <option value="Assistant Pastor"disabled>Assistant Pastor</option> 
                                        </select>
                                    </div>
                                    <div class="form-group">

                                       <label for="department">Department / Affiliate(<i>If Anay</i>)</label>
                                        <input type="text" class="form-control" name="department">
                                    </div>
                                    <div class="form-group">
                                        <label>Branch District<span class="login-danger">*</span></label>
                                        <select class="form-control" name="branch_region" required>
                                            <option value="" disabled selected >Select District</option>
                                            <option value="Bolga East">Bolga East</option>
                                            <option value="Nabdam">Nabdam</option>
                                            <option value="Bongo">Bongo</option>
                                             <option value="Bawku West">Bawku West</option>
                                            <option value="Bolga Municipal">Bolga Municipal</option>
                                            <option value="Talensi">Talensi</option>
                                         </select>
                                         </div>
                               
                                <div class="form-group">
                                        <label>Branch Name<span class="login-danger">*</span></label>
                                        <select class="form-control" name="branch_name" required>
                                            <option value="" disabled selected >Select Branch</option>
                                            <option value="Prayer Temple -Zuarungu">Prayer Temple - Zuarungu</option>
                                            <option value="Revival Temple-Kongo">Revival Temple - Kongo</option>
                                            <option value="Power Temple-Akayonga">Power Temple-Akayonga</option>
                                             <option value="Greater love Temple-Adaboya">Greater love Temple - Adaboya</option>
                                            <option value="Dunamis Temple - Kamega">Dunamis Temple - Kamega</option>
                                            <option value="Miracle Temple -Tonde">Miracle Temple - Tonde</option>
                                            <option value="Fire Temple-Sherigu">Fire Temple - Sherigu</option>
                                            <option value="Glory Temple -Yebongo">Glory Temple - Yebongo</option>
                                            <option value="Mercy Temple -Sheagu">Mercy Temple - Sheagu</option>
                                         </select>
                                         </div>
                                    <div class="form-group">
                                        <label for="title">Title <span class="login-danger">*</span> (Mr / Mrs / Pastor etc.)</label>
                                        <input type="text" class="form-control" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name">First Name<span class="login-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name<span class="login-danger">*</span></label>
                                        <input type="text" class="form-control" name="last_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="date_assigned">Date <span class="login-danger">*</span>(date joined)</label>
                                        <input type="date" class="form-control" name="date_assigned" required>
                                    </div>
                                    <div class="row2">
                                        <div class="form-group">
                                            <label for="sex">Sex<span class="login-danger">*</span></label>
                                            <select class="form-control" name="sex" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="dob">Date Of Birth<span class="login-danger">*</span></label>
                                            <input type="date" class="form-control" name="dob" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">Phone (Mobile)<span class="login-danger">*</span></label>
                                            <input type="tel" class="form-control" name="mobile" placeholder="(+233)" required >
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address<span class="login-danger">*</span></label>
                                            <input type="" class="form-control" name="address" required>
                                        </div>

                                        <!--                 /*attendance register form*/-->
                                        <!-- <div class="container">
                                             <hr> <h1>Attendance Register</h1><hr>
                                        
                                               
<?php
require 'connect.php';

// Initialize variables for error handling
$pastor_name = $status = $total_presents = $total_absents = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate staff name (not empty)
    if (empty($_POST['pastor_name'])) {
        $error = "Staff Name is required.";
    } else {
        $teacher_name = $_POST['pastor_name'];
        $status = $_POST['status'];
        $total_presents = $_POST['total_presents'];
        $total_absents = $_POST['total_absents'];

        // Check if the student already exists in the database
        $checkQuery = "SELECT COUNT(*) FROM staff_attendance WHERE pastor_name = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("s", $teacher_name);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            $error = "Pastor with the same name already exist in Register.";
        } else {
            // Insert data into the database if validation passes
            $insertQuery = "INSERT INTO staff_attendance (pastor_name, time_arrived, status, total_presents, total_absents) VALUES (?, NOW(), ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssii", $pastor_name, $status, $total_presents, $total_absents);

            if ($stmt->execute()) {
                header('location:sucess.php');
                // Clear the form fields
                $pastor_name = $status = $total_presents = $total_absents = "";
            } else {
                echo '<p class="text-danger">Error adding member: ' . $stmt->error . '</p>';
            }

            $stmt->close();
        }
    }
}
?>
                                        
                                                <form method="POST" action="">
                                                    <div class="form-group">
                                        <?php
                                        // Display validation error, if any
                                        if (!empty($error)) {
                                            echo '<p class="text-danger">' . $error . '</p>';
                                        }
                                        ?>
                                                        <label for="pastor_name">Pastors Full Name:</label>
                                                        <input type="text" class="form-control" id="pastor_name" name="pastor_name" value="<?php echo htmlspecialchars($pastor_name); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="status">Status:</label>
                                                        <select class="form-control" id="status" name="status" required>
                                                            <option value="Present" <?php if ($status === 'Present') echo 'selected'; ?>>Present</option>
                                                            <option value="Absent" <?php if ($status === 'Absent') echo 'selected'; ?>>Absent</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="total_presents">Total Presents:</label>
                                                        <input type="number" class="form-control" id="total_presents" name="total_presents" value="<?php echo htmlspecialchars($total_presents);
                                        +0 ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="total_absents">Total Absents:</label>
                                                        <input type="number" class="form-control" id="total_absents" name="total_absents" value="<?php echo htmlspecialchars($total_absents);
                                        +0 ?>" required>
                                                    </div>
                                                    
                                                </form>
                                        
                                              
                                            </div>-->
                                        <div class="form-group checkbox">
                                            <input type="checkbox" id="cterms" value="Agreed-to-Terms"class="check1"style="margin-right:10px;" required >I have read and agreed to <a href="#"> House Of Grace Evangelical Church App</a> stated conditions in <a href="#">Privacy, Policy</a> <a href="#">Terms </a>and <a href="#">Conditions.</a> 
                                            <div class="help-block with-errors"></div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <button class="btn btn-primary btn-block" type="submit">Register</button>
                                            <div class=" dont-have">Already Registered ? <a href="admin_main.php">Login</a></div>
                                        </div>
                                </form>

                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">follow</span>
                                </div>

                                <div class="social-login"style="margin-bottom: 10px">
                                    <a href="#"><i class="fab fa-google-plus-g"></i></a>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                </div>

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

    <script src="assets/js/script.js"></script>
</body>
</html>


