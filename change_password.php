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
        <title>Set New Password</title>
        <link href="assets/css/main.css" rel="stylesheet">

        <link rel="shortcut icon" href="assets/img/logo1.png">

        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

        <link rel="stylesheet" href="assets/plugins/feather/feather.css">

        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <style>
        .main-alert{
            color:green;
            width: 30%;
            font-size: 16px;
            font-family: sans-serif;
            margin-bottom: 30px;
            padding: 10px;


        }
        .form-group{
            margin: 20px auto;
        }
        .container{
            margin: 10px auto;
        }
    </style>
    <body>
        <div class="container mt-5" style="width:400px">
            <h1 class="title  mb-4">Change Password</h1>
            <?php
            require 'session.php';

            require_once 'connect.php';

            $passwordError = $successMessage = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($newPassword !== $confirmPassword) {
                    $passwordError = "Passwords did not match. Please try again.";
                } else {
                    $reset_user = "";
                    $username = $_SESSION['username'];

                    $updatePasswordQuery = "UPDATE staff SET password = '$newPassword' WHERE username = '$username'";

                    if (mysqli_query($conn, $updatePasswordQuery)) {
                        unset($_SESSION['reset_user']);
                        $successMessage = "Password updated successfully.  <a href='login.php' class=''>Click here</a> to continue";
                    } else {
                        $passwordError = "Password update failed. Please try again later.";
                    }
                }
            }
            ?>
            <div class="form-box">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div>
<?php if (!empty($passwordError)) { ?>
                            <div class="alert alert-danger mt-3">
                            <?php echo $passwordError; ?>
                            </div>
                            <?php } elseif (!empty($successMessage)) { ?>
                            <div class="alert alert-success mt-4 main-alert">
                            <?php echo $successMessage; ?>
                            </div>
                            <?php } ?>

                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                    <a href="admin_main.php" class="btn btn-info">Back</a>
                </form>

            </div>
        </div>
    </body>
</html>
