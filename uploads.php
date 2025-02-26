
<?php
require('session.php');
require('connect.php');

// $allowed_user_types = array('Branch_Pastor','Assistant_Pastor','Admin');
// if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], $allowed_user_types)) {
//     header('location:login.php');
//     exit; // Terminate script execution after redirection
// }

// Check if a file was uploaded
if (isset($_FILES['file_upload'])) {
    $file = $_FILES['file_upload'];

    // Validate and sanitize the uploaded file name
    $file_name = mysqli_real_escape_string($conn, $file['name']);

    // Additional data to insert
    $branch_name = isset($_POST['branch_name']) ? mysqli_real_escape_string($conn, $_POST['branch_name']) : null;
    $note_title = isset($_POST['note_title']) ? mysqli_real_escape_string($conn, $_POST['note_title']) : null;
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : null;

    // Query to check if the branch name exists in the staff table
    $check_query = "SELECT * FROM staff WHERE branch_name = '$branch_name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Branch name exists, proceed with file upload and database insertion

        // Specify the directory where you want to store uploaded files
        $upload_directory = "service_reports/"; // Create this directory if it doesn't exist
        $file_path = './service_reports/' . $file_name;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // File upload successful, now update the database
            $query = "INSERT INTO service_reports (branch_name, note_title, file_name, status, file_path) VALUES ('$branch_name', '$note_title', '$file_name', '$status', '$file_path')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                // File information successfully added to the database
                $successMessage = "Report successfully uploaded";
                // header('location:admin_main.php');
            } else {
                $error = 'Error updating the database: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Error moving the uploaded file to the server.';
        }
    } else {
        $error = "Branch name '$branch_name' does not exist in the database.";
    }
} else {
    $error = 'No file was uploaded.';
}

// If there was an error, handle it appropriately (e.g., display an error message).
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Report</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-+fqpP2ZDxLg7gyjtCprz+TazlK9pCNhYLlORokjR5tXUtn5ax/+F/Jhb3/Th4XfjReJsqXwDCAlcZ3cDk2Qlg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/admin.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-group h3 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-sizing: border-box;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 1rem;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .success-message,
        .error-message {
            text-align: center;
            margin-top: 1rem;
        }

        .success-message {
            color: #28a745;
        }

        .error-message {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-group">
            <h3>Upload a Service Report</h3>
            <a href="admin_main.php" class="btn btn-primary">Back</a>
            <form method="post" action="uploads.php" enctype="multipart/form-data">
                <label for="branch_name">Branch Name</label>
                <input type="text" id="branch_name" name="branch_name" required>

                <label for="note_title">Service Date</label>
                <input type="date" id="note_title" name="note_title" required>

                <label for="service_reports">Upload Report (Pdf, Word, PNG, JPG, etc.)</label>
                <input type="file" id="service_reports" name="file_upload" accept=".pdf, .jpeg, .jpg, .png, .docx" required>

                <input type="submit" value="Upload report">
            </form>
        </div>

        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success success-message"><?= $successMessage ?></div>
        <?php endif; ?>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger error-message"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
