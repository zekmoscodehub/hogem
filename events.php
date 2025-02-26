<?php
session_start();
include 'connect.php';

// Fetch events with uploaded_by and event_date fields
$query = "SELECT id, title, description, event_date, image, uploaded_by FROM events ORDER BY event_date ASC";
$result = $conn->query($query);

// Get user type from session
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .event-card { border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s; }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
        .event-image { max-width: 100%; height: auto; border-radius: 8px; }
        .btn-sm { margin-right: 5px; }
        @media (max-width: 576px) { .event-card { padding: 10px; } .event-title { font-size: 1.2rem; } }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">Upcoming Events</h2>

    <!-- Add Event Button -->
    <div class="text-end mb-4">
        <a href="admin_main.php" class="btn btn-info">Back</a>
        <?php if ($user_type == 'Admin' || $user_type == 'Branch Pastor'): ?>
            <a href="add_event.php" class="btn btn-success">Add Event</a>
        <?php else: ?>
            <button class="btn btn-success" disabled>Add Event</button>
        <?php endif; ?>
    </div>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='event-card p-4 mb-4 bg-white'>
                    <h5 class='event-title'>" . htmlspecialchars($row['title']) . "</h5>";

            // Display event image if available
            if (!empty($row['image']) && file_exists($row['image'])) {
                echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Event Image' class='event-image my-3'>";
            }

            echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>
                  <p class='text-muted'><strong>Event Date:</strong> " . htmlspecialchars(date("F j, Y", strtotime($row['event_date']))) . "</p>
                  <p class='text-muted'><strong>Uploaded by:</strong> " . htmlspecialchars($row['uploaded_by']) . "</p>
                  <div class='d-flex justify-content-end'>";

            // Admin can edit & delete
            if ($user_type == 'Admin') {
                echo "<a href='edit_event.php?id=" . urlencode($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a>
                      <a href='delete_event.php?id=" . urlencode($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this event?\");'>Delete</a>";
            } else {
                echo "<button class='btn btn-warning btn-sm' disabled>Edit</button>
                      <button class='btn btn-danger btn-sm' disabled>Delete</button>";
            }

            echo "</div></div>";
        }
    } else {
        echo "<p class='text-muted text-center'>No upcoming events.</p>";
    }
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
