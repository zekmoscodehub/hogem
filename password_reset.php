<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="HoGEM" content="HoGEM" /> <!-- website name -->
        <meta property="HoGEM" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="HoGEM App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
        <title>Set New Password</title>
        <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

        <link rel="stylesheet" href="assets/plugins/feather/feather.css">

    </head>
    <style>
        .main-alert{
            color:green;
            width: 50%;
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
            align-items:center;
            justify-content:center;
            text-align:center;
        }
        .card{
            margin:20px auto;
            align-items:center;
            justify-content:center;
            
        }
        h1{
            margin-top:20px;
            color: coral;
        }
        .btn{
            margin:20px;
        }
    </style>
    <body>
    <div class="container">
        <div class="card mt-5" style="width:400px">
            <div class="h1"><h1 class="mb-4">Welcome!</h1></div>
            <h3 class="mb-4">Please, set your new Password</h3>
            <?php
            session_start();

            if (!isset($_SESSION['reset_user'])) {
                header('Location: password_reset_request.php');
                exit;
            }

            require_once 'connect.php';

            $passwordError = $successMessage = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($newPassword !== $confirmPassword) {
                    $passwordError = "Passwords did not match. Please try again.";
                } else {
                    $email = $_SESSION['reset_user'];

                    $updatePasswordQuery = "UPDATE staff SET password = '$newPassword' WHERE email = '$email'";

                    if (mysqli_query($conn, $updatePasswordQuery)) {
                        unset($_SESSION['reset_user']);
                        $successMessage = "Changed! <a href='login.php' class=''>Click here</a> to continue";
                    } else {
                        $passwordError = "Password update failed. Please try again later.";
                    }
                }
            }
            ?>
            <div class="form-box">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div >
                        <?php if (!empty($passwordError)) { ?>
                            <div class="alert alert-danger mt-3" style="width:300px;">
                                <?php echo $passwordError; ?>
                            </div>
                        <?php } elseif (!empty($successMessage)) { ?>
                            <div class="alert alert-success mt-4 main-alert" style="width:300px;">
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
                </form>

            </div>
        </div>
                        </div>
    </body>
</html>
