<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News and Events</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .events-news {
            margin-top: 50px;
        }
        .list-group-item {
            margin-bottom: 20px;
        }
        .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container events-news">
    <div class="row">
        <!-- News Section -->
        <div class="col-md-6">
            <h2 class="text-primary text-center">Latest News</h2>
            <div class="list-group">
                <?php
                // Fetch news
                $query = "SELECT id, title, content FROM news ORDER BY created_at DESC";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='list-group-item'>
                                <h5 class='mb-2 text-dark font-weight-bold'>" . htmlspecialchars($row['title']) . "</h5>
                                <p class='text-muted'>" . htmlspecialchars($row['content']) . "</p>
                                <div class='d-flex justify-content-end'>
                                    <a href='edit_news.php?id=" . urlencode($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='add_news.php' class='btn btn-success btn-sm'>Add News</a>
                                    <a href='delete_news.php?id=" . urlencode($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this news item?\");'>Delete</a>
                                </div>
                              </div>";
                    }
                } else {
                    echo "<p class='text-muted text-center'>No news available.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Events Section -->
        <div class="col-md-6">
            <h2 class="text-success text-center">Upcoming Events</h2>
            <div class="list-group">
                <?php
                // Fetch events
                $query = "SELECT id, title, description, event_date FROM events ORDER BY event_date ASC";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='list-group-item'>
                                <h5 class='mb-2 text-dark font-weight-bold'>" . htmlspecialchars($row['title']) . "</h5>
                                <p class='text-muted'>" . htmlspecialchars($row['description']) . "</p>
                                <small class='text-muted d-block'>Event Date: " . htmlspecialchars($row['event_date']) . "</small>
                                <div class='d-flex justify-content-end'>
                                    <a href='edit_event.php?id=" . urlencode($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='add_event.php' class='btn btn-success btn-sm'>Add Event</a>
                                    <a href='delete_event.php?id=" . urlencode($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this event?\");'>Delete</a>
                                </div>
                              </div>";
                    }
                } else {
                    echo "<p class='text-muted text-center'>No upcoming events.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="nav-links">
    <a href="admin_main.php" class="btn btn-success">Back</a>
   
            </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
