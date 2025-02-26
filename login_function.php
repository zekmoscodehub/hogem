<?php

require 'connect.php';

function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = clean($_POST['username']);
    $password = clean($_POST['password']);
    
    // Query to check user credentials and confirmation status
    $sql = "SELECT * FROM staff WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['confirmed'] === 'No') {
            echo '<p style="color:red; text-align:center;">Account is undergoing confirmation process, allow within 2 - 6 hours. Thank you for your patience.</p>';
        } else {
            // Valid user, set session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['branch_name'] = $row['branch_name'];
            
            // Redirect based on user role
            switch ($row['user_type']) {
                case 'Admin':
                    header("Location: admin_main.php");
                    break;
                case 'Branch Pastor':
                    header("Location: branch_main.php");
                    break;
                case 'Assistant Pastor':
                    header("Location: ass.pastor_main.php");
                    break;
                case 'Pastor':
                    header("Location: pastor_main.php");
                    break;
                case 'Worker':
                    header("Location: worker-main.php");
                    break;
                case 'Member':
                    header("Location: member-main.php");
                    break;
                default:
                    echo "<p style='color:red; text-align:center;'>Page error</p>";
                    break;
            }
        }
    } else {
        echo '<p style="color:red; text-align:center;">Invalid username or password!</p>';
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
