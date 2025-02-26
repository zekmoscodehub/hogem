<?php
// Start the session
session_start();
require 'connect.php';
require 'login_function.php'; // Assuming this has your login logic

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ✅ Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['errprompt'] = "Please fill in all fields.";
    } else {
        // Query the staff table
        $stmt = $conn->prepare("SELECT id, username, password, user_type, branch_name FROM staff WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // ✅ Check if user exists
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Check password (use password_hash in real scenarios)
            if (password_verify($password, $user['password'])) {
                // ✅ Set session variables
                $_SESSION['staff_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['branch_name'] = $user['branch_name'];

                // Redirect to dashboard after login
                header("Location: admin_main.php");
                exit();
            } else {
                $_SESSION['errprompt'] = "Incorrect password.";
            }
        } else {
            $_SESSION['errprompt'] = "User not found.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Page - HOUSE OF GRACE</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>

<section class="center-text form">
    <img src="assets/img/logo1.png" class="logo" alt="Logo"><br>
    <strong>Log In</strong>

    <div class="login-form box-center form">
        <?php
        if (isset($_SESSION['errprompt'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['errprompt'] . "</div>";
            unset($_SESSION['errprompt']);
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button><br>
            <div>
                Forgot password? <a href="password_reset_request.php">Click here</a><br>
                Not registered? <a href="join.php">Register</a>
            </div>
        </form>
    </div>
</section>

<script src="assets/js/jquery-3.1.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
