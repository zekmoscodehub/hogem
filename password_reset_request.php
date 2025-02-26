
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
        <title>Reset Password</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/plugins/feather/feather.css">
        <!--<link rel="stylesheet" href="assets/css/bootstrap.min.css"/>-->
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
        <!--    <link rel="stylesheet" href="assets/css/admin.css">-->
        <style>
            .container{

                justify-content: center;
                align-items: center;
                margin:10px 20rem;
            }
            form{
                margin:10px;
                align-items: center;


            }
            .case-box,.alert{
                width: 100vmin;
                margin: 10px;
            }
            .lab{
                color:red;
            }
            @media screen and (max-width:400px){
                body{
                    color:yellow;
                }
                .container{
                    top: 10px;
                    left:20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container ">

            <?php
            require_once 'connect.php';
            session_start();

            $emailError = $branchNameError = $mobileError = $dobError = $errorMessage = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'];
                $branch_name = clean($_POST['branch_name']);
                $mobile = clean($_POST['mobile']);
                $dob = clean($_POST['dob']);

                $email = filter_var($email, FILTER_SANITIZE_EMAIL);

                $check_user_query = "SELECT * FROM staff WHERE email = '$email' AND branch_name = '$branch_name' AND mobile = '$mobile' AND dob = '$dob'";
                $result = mysqli_query($conn, $check_user_query);

                if (mysqli_num_rows($result) > 0) {
                    $_SESSION['reset_user'] = $email;
                    header('Location: password_reset.php');
                    exit;
                } else {
                    $errorMessage = "Invalid credentials. Please try with valid credentials.";
                }
            }

            function clean($data) {
                global $conn;
                return mysqli_real_escape_string($conn, $data);
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <?php if (!empty($errorMessage)) { ?>
                    <div class="alert alert-danger mt-3" style="background:red;color:#fff; width:350px;text-align: center;">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php } ?>
                <div class="case-box">
                    <h1 class="mb-4">Reset Password </h1>
                    <label class="lab"><i>Please enter all fields</i></label>
                    <div class="form-group">
                        <label for="email">Email<span class="login-danger"> *</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="branch_name">Branch Name<span class="login-danger"> *</span></label>
                        <input type="text" name="branch_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Phone (Mobile)<span class="login-danger"> *</span></label>
                        <input type="tel" name="mobile" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth<span class="login-danger"> *</span></label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Verify Identity</button>
                </div>
            </form>

        </div>
    </body>
</html>
