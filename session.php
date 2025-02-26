<?php
session_start();
error_reporting(E_ALL);  // Use E_ALL for cleaner error reporting

require('connect.php');

// ✅ Regenerate session ID on login for security
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// ✅ Set session count for tracking (if needed)
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 1;
} else {
    $_SESSION['count']++;
}

// ✅ Retrieve session variables
$staff_id = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';

// ✅ Debug session data if issues persist
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/

// ✅ Optional: Last visited page
$_SESSION['last_visited_page'] = basename($_SERVER['PHP_SELF']);
?>
