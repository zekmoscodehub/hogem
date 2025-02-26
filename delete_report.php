
<?php
require('session.php');
require('connect.php');

// Initialize variables
$successMessage = '';
$error = '';

// Handle delete request
if (isset($_POST['delete_report'])) {
    $report_id = $_POST['delete_report'];

    // Query to delete the report from the database
    $delete_query = "DELETE FROM service_reports WHERE service_report_id = '$report_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        $successMessage = "Report successfully deleted";
        header('location:admin-services-report.php');
    } else {
        $error = 'Error deleting the report: ' . mysqli_error($conn);
    }
}