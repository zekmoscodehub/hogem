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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $uploaded_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'news_images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
        }

        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . time() . '_' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Image uploaded successfully
        } else {
            $error_message = "Image upload failed.";
        }
    }

    // Insert news into database
    if ($uploaded_by) {
        $query = "INSERT INTO news (title, content, image, uploaded_by) 
                  VALUES ('$title', '$content', '$image_path', '$uploaded_by')";

        if ($conn->query($query)) {
            $success_message = "News added successfully.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "Invalid user session. Please log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Add News</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="add_news.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Add News</button>
    </form>

    <a href="news.php" class="btn btn-secondary mt-3">Back to News</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
