<?php
session_start();
include 'connect.php';

// Fetch news with branch_name of uploader
$query = "SELECT news.id, news.title, news.content, news.image, staff.branch_name 
          FROM news 
          JOIN staff ON news.uploaded_by = staff.id 
          ORDER BY news.created_at DESC";
$result = $conn->query($query);

// Get logged-in user type from session
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest News</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .news-card {
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .news-image {
            max-width: 60%;
            height: auto;
            border-radius: 8px;
        }
        .btn-sm {
            margin-right: 5px;
        }
        @media (max-width: 576px) {
            .news-card {
                padding: 10px;
            }
            .news-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">Latest News</h2>

    <!-- Add News Button -->
    <div class="text-end mb-4">
        <?php if ($user_type == 'Admin' || $user_type == 'Branch Pastor'): ?>
            <a href="add_news.php" class="btn btn-success">Add News</a>
        <?php else: ?>
            <button class="btn btn-success" disabled>Add News</button>
        <?php endif; ?>
    </div>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='news-card p-4 mb-4 bg-white'>
                    <h5 class='news-title'>" . htmlspecialchars($row['title']) . "</h5>";

            // Display image if available
            if (!empty($row['image']) && file_exists($row['image'])) {
                echo "<img src='" . htmlspecialchars($row['image']) . "' alt='News Image' class='news-image my-3'>";
            }

            echo    "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>

                    <!-- Branch Name Below Content -->
                    <p class='text-muted'><strong>Branch:</strong> " . htmlspecialchars($row['branch_name']) . "</p>

                    <div class='d-flex justify-content-end'>";

            // Button logic based on user type
            if ($user_type == 'Admin') {
                echo "<a href='edit_news.php?id=" . urlencode($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a>
                      <a href='delete_news.php?id=" . urlencode($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this news item?\");'>Delete</a>";
            } else {
                echo "<button class='btn btn-warning btn-sm' disabled>Edit</button>
                      <button class='btn btn-danger btn-sm' disabled>Delete</button>";
            }

            echo    "</div>
                  </div>";
        }
    } else {
        echo "<p class='text-muted text-center'>No news available.</p>";
    }
    ?>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
