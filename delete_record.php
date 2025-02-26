<?php
// delete_record.php

// Database connection
require('session.php');
require('connect.php');
// Check if the user is logged in and has admin privileges
if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    // Redirect to another page or display an error message
    header("Location:index.php");
    exit(); // Ensure script execution stops
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if record_id is set and not empty
    if (isset($_POST['record_id']) && !empty($_POST['record_id'])) {
        $record_id = $_POST['record_id'];

        // Prepare a SQL statement to delete the record
        $sql = "DELETE FROM finance_table WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind the parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "i", $record_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // If deletion is successful, send a success response
            echo "Record deleted successfully.";
        } else {
            // If deletion fails, send an error response
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        // If record_id is not set or empty, send an error response
        echo "Invalid record ID.";
    }
} else {
    // If the request method is not POST, send an error response
    echo "Invalid request method.";
}


