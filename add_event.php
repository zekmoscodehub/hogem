<?php
require ('session.php');
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    $_SESSION['user_type'] = 'Guest'; // Default user type if not logged in
}

// Initialize variables
$success_message = "";
$error_message = "";
$image_path = "";

// Get branch name from session
$uploaded_by = isset($_SESSION['branch_name']) ? $_SESSION['branch_name'] : 'Unknown Branch';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $event_date = $conn->real_escape_string($_POST['event_date']);

    // Validate inputs
    if (empty($title) || empty($description) || empty($event_date)) {
        $error_message = "All fields are required.";
    } elseif ($uploaded_by === 'Unknown Branch') {
        $error_message = "Branch name not found. Please log in again.";
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'event_images/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
            }

            $image_name = basename($_FILES['image']['name']);
            $image_path = $upload_dir . time() . '_' . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $error_message = "Image upload failed.";
            }
        }

        // Insert event into database if no errors
        if (empty($error_message)) {
            $query = "INSERT INTO events (title, description, event_date, image, uploaded_by) 
                      VALUES ('$title', '$description', '$event_date', '$image_path', '$uploaded_by')";

            if ($conn->query($query)) {
                $success_message = "Event added successfully.";
            } else {
                $error_message = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Add Event</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="add_event.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Event Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Event Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="event_date" class="form-label">Event Date</label>
            <input type="date" name="event_date" id="event_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Upload Event Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Add Event</button>
    </form>

    <a href="view_events.php" class="btn btn-secondary mt-3">Back to Events</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
