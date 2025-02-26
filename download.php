<?php
// Include your database connection and any other necessary includes
require 'session.php';
require 'connect.php';

// Initialize variables
$error = '';

// Check if a lesson note ID is provided in the query string
if (isset($_GET['service_report_id'])) {
    $service_report_id = $_GET['service_report_id'];

    // Validate and sanitize the input (you can add more validation)
    if (ctype_digit($service_report_id)) {
        // Query the database to fetch the file information
        $query = "SELECT * FROM service_reports WHERE service_report_id = $service_report_id"; // Update the table name if necessary
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $file_name = $row['file_name'];
            $file_path = $row['file_path']; // Update this to match your database schema
            $file_type = $row['file_type']; // Update this to match your database schema
            // Set the appropriate headers for the download
            header('Content-Type: ' . $file_type);
            header('Content-Disposition: attachment; filename="' . $file_name . '"');

            // Read and output the file content
            readfile($file_path);
            exit;
        } else {
            $error = 'Reports not found.';
        }
    } else {
        $error = 'Invalid report ID format.';
    }
} else {
    $error = 'Report ID is missing.';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="HOUSE OF GRACE" content="HOUSE OF GRACE" /> <!-- website name -->
        <meta property="HOUSE OF GRACE" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="House Of Grace Evangelical Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"> <!-- title shown in the actual shared post -->
        <title>Download Church Service Reports</title>
        <link rel="shortcut icon" href="assets/img/i33--1x-1.png">
    </head>
    <body>
        <?php if ($error) : ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </body>
</html>


