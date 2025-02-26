<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House of Grace - News & Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">House of Grace Evangelical Ministry</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h2 class="text-primary">Latest News</h2>
                <div class="list-group">
                    <?php include 'news.php'; ?>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="text-success">Upcoming Events</h2>
                <div class="list-group">
                    <?php include 'events.php'; ?>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="add_news.php" class="btn btn-primary">Add News</a>
            <a href="add_event.php" class="btn btn-success">Add Event</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
