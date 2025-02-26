<!-- <?php
//databass connection
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

function showPrompt() {
    if (isset($_SESSION['prompt'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['prompt'] . "</div>";
        unset($_SESSION['prompt']);
    }
}

function showError() {
    if (isset($_SESSION['errprompt'])) {
        echo "<div class='alert alert-danger'>" . $_SESSION['errprompt'] . "</div>";
        unset($_SESSION['errprompt']);
    }
}

function verify_credentials($username, $password) {
    $conn = connectToDatabase(); // Assuming you have a function called connectToDatabase() that establishes the database connection

    // Prepare the query to fetch the user data based on the username
    $query = "SELECT id, username, password, user_type FROM staff WHERE username = ?,?,?,?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $fetched_username, $hashed_password, $user_type);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Check if the user exists and the password is correct
    if (password_verify($password, $hashed_password) && $fetched_username === $username) {
        $_SESSION['user_type'] = $user_type; // Store the user type in the session
        return $user_id; // Return the user ID if the credentials are valid
    }

    return false; // Return false if the credentials are invalid
}

function update_session_token($username, $session_token) {
    $conn = connectToDatabase();

    $query = "UPDATE users SET session_token = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $session_token, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
function is_user_logged_in() {
    if (isset($_SESSION['session_token'])) {
        // You might want to validate the session token against the database here
        return true;
    }
    return false;
}

// Function to redirect to the appropriate dashboard
function redirect_to_dashboard() {
    if (is_admin()) {
        header('Location: admin_main.php');
    } elseif (is_staff()) {
        header('Location: staff_main.php');
    } elseif (is_siso()) {
        header('Location: admin_main.php');
    } elseif (is_siso()) {
        header('Location: admin_profile.php');
    } else {
        // Default redirect to the login page in case the user type is not recognized
        header('Location: login.php');
    }
    exit;
} -->
