<?php
// Include the database connection file
include 'connect.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    // Retrieve and sanitize the 'id' parameter
    $id = intval($_GET['id']);

    // Prepare the DELETE statement
    $query = "DELETE FROM news WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the main page after successful deletion
        header("Location: events-index.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
